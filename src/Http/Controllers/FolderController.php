<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FolderController extends AssetManagerController
{
    /**
     * Get a folder's contents and their metadata.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $files = collect($this->storage->listContents($this->path));

        $this->enrich($files);

        if ($request->has('mimes')) {
            $files = $this->filterMimes($files, $request->mimes);
        }

        $files = $this->sort($files);

        return response()->json([
            'files' => $files,
        ]);
    }

    /**
     * Create a directory with the given name at the given path.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $folder = preg_replace("/[^a-zA-Z0-9 _-]/", '', $request->folder);

        $fullPath = $request->path.'/'.$folder;

        if ($this->storage->has($fullPath)) {
            return response('folder_exists');
        }

        if ($this->storage->makeDirectory($fullPath)) {
            return response('success');
        } else {
            return response()->json(false);
        }
    }

    /**
     * Delete the directory at the given path.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->storage->deleteDirectory($request->path)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    /**
     * Augment file metadata.
     */
    protected function enrich($files)
    {
        $files->transform(function ($file, $key) {
            $file['id'] = md5($file['path']);

            $file['url'] = $this->storage->url($file['path']);

            if ($file['type'] == 'dir') {
                $file['mime'] = 'folder';
            } else {
                $file['mime'] = $this->getMime($file['path']);
                $file['size_string'] = $this->format_size($file['size']);
            }

            return $file;
        });
    }

    /**
     * Sort files by type then by name, with folders first.
     */
    protected function sort($files)
    {
        $folders = $files->where('type', 'dir')->sortBy('basename')->values();

        $items = $files->where('type', 'file')->sortBy(function ($item) {
            return $item['mime'] . '-' . $item['basename'];
        })->values();

        return $folders->merge($items);
    }

    /**
     * Filter files, returning only those with one of the given mime types.
     */
    protected function filterMimes($files, $mimes)
    {
        return $files->filter(function ($value, $key) use ($mimes) {
            return in_array($value['mime'], $mimes);
        });
    }

    /**
     * Format file size as a nice string.
     */
    protected function format_size($bytes)
    {
        if ($bytes == 0) return '0.0 KB';

        $suffixes = ['B', 'KB', 'MB', 'GB'];
        $thousands = floor(log($bytes, 1024));

        return round($bytes/pow(1024, $thousands), 1) . ' ' . $suffixes[$thousands];
    }

    /**
     * Retrieve and categorize a file's mime type.
     */
    protected function getMime($path)
    {
        $mime = $this->storage->mimeType($path);

        switch($mime) {
            case 'directory':
                return 'folder';

            case Str::startsWith($mime, 'image'):
                return 'image';

            case 'application/pdf':
                return 'pdf';

            case Str::startsWith($mime, 'audio'):
                return 'audio';

            case Str::startsWith($mime, 'video'):
                return 'video';

            case Str::endsWith($mime, 'zip'):
                return 'archive';

            case Str::startsWith($mime, 'text'):
                return 'text';

            case 'application/msword':
                return 'word';

            case 'application/vnd.ms-excel':
                return 'excel';

            case 'application/vnd.ms-powerpoint':
                return 'powerpoint';

            default:
                return 'file';
        }
    }
}
