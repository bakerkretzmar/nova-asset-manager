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

        $files = $this->filterDotfiles($files);

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
     * Filter files, removing those with names beginning with a '.'.
     */
    protected function filterDotfiles($files)
    {
        return $files->filter(function ($value, $key) {
            return ! Str::startsWith($value['basename'], '.');
        });
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

        $word = [
            'application/msword',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'application/vnd.ms-word.document.macroEnabled.12',
            'application/vnd.ms-word.template.macroEnabled.12',
        ];

        $excel = [
            'application/vnd.ms-excel',
            'application/vnd.ms-excel',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'application/vnd.ms-excel.sheet.macroEnabled.12',
            'application/vnd.ms-excel.template.macroEnabled.12',
            'application/vnd.ms-excel.addin.macroEnabled.12',
            'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
        ];

        $powerpoint = [
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.presentationml.template',
            'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'application/vnd.ms-powerpoint.addin.macroEnabled.12',
            'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
            'application/vnd.ms-powerpoint.template.macroEnabled.12',
            'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
        ];

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

            case in_array($mime, $word):
                return 'doc-word';

            case in_array($mime, $excel):
                return 'doc-excel';

            case in_array($mime, $powerpoint):
                return 'doc-powerpoint';

            default:
                return 'file';
        }
    }
}
