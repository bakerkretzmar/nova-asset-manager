<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Http\Request;

class FolderController extends AssetManagerController
{
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
    public function delete(Request $request)
    {
        if ($this->storage->deleteDirectory($request->path)) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }
}
