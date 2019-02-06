<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Bakerkretzmar\AssetManager\Http\Services\NormalizeFile;

use Illuminate\Http\Request;

class FileController extends AssetManagerController
{
    /**
     * Get detailed information about a given file.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $fullPath = $this->storage->path($request->path);

        $info = new NormalizeFile($this->storage, $fullPath, $request->path);

        return response()->json($info->toArray());
    }

    /**
     * Upload a file at the given path.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function create(Request $request)
    {
        if ($this->storage->has($request->path . '/' . $request->file->getClientOriginalName())) {
            return response('file_exists');
        }

        // Check size and dimensions!!

        if ($this->storage->putFileAs($request->path, $request->file, $request->file->getClientOriginalName())) {
            return response('success');
        } else {
            return response()->json(false);
        }
    }

    /**
     * Delete a given file from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->storage->delete($request->path)) {
            return response('success');
        } else {
            return response()->json(false);
        }
    }
}
