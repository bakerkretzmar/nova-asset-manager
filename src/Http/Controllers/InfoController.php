<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InfoController extends AssetManagerController
{
    public function __invoke(Request $request)
    {
        $path = $request->path;

        if (! $this->storage->exists($path)) {
            $path = '/';
        }

        $files = $this->storage->listContents($path);

        foreach ($files as $key => $file) {
            $files[$key]['id'] = md5($file['path']);
            $files[$key]['name'] = $file['basename'];
            $files[$key]['url'] = $this->storage->url($file['path']);

            if ($file['type'] == 'dir') {
                $files[$key]['type'] = 'folder';
            } else {
                $files[$key]['type'] = $this->getMime($file['path']);
                $files[$key]['size_string'] = $this->format_size($file['size']);
            }
        }

        usort($files, function($a, $b) {
            return strnatcmp($a['type'], $b['type']);
        });

        return response()->json([
            'files' => $files,
            'path' => $path,
        ]);
    }

    protected function format_size($bytes)
    {
        if ($bytes == 0) return '0.0 KB';

        $suffixes = ['KB', 'MB', 'GB'];
        $thousands = floor(log($bytes, 1024));

        return round($bytes/pow(1024, $thousands), 1) . $suffixes[$thousands];
    }

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
