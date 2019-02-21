<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class AssetManagerController extends Controller
{
    protected $path;

    protected $storage;

    public function __construct(Request $request)
    {
        $this->path = $request->path;

        $this->storage = Storage::disk(config('filesystems.asset_manager_disk', 'assets'));
    }
}
