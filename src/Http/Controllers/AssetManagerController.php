<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class AssetManagerController extends Controller
{
    /** @var \Illuminate\Support\Facades\Storage */
    protected $storage;

    public function __construct()
    {
        $this->storage = Storage::disk('assets');
    }
}
