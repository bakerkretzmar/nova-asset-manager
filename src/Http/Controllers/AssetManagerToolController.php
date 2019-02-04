<?php

namespace Bakerkretzmar\AssetManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Bakerkretzmar\AssetManager\Http\Services\AssetManagerService;

class AssetManagerToolController extends Controller
{
    /**  @var mixed  */
    protected $service;

    /**
     * @param AssetManagerService $service
     */
    public function __construct(AssetManagerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     */
    public function getData(Request $request)
    {
        return $this->service->ajaxGetFilesAndFolders($request);
    }

    /**
     * @param Request $request
     */
    public function createFolder(Request $request)
    {
        return $this->service->createFolderOnPath($request->folder, $request->current);
    }

    /**
     * @param Request $request
     */
    public function deleteFolder(Request $request)
    {
        return $this->service->deleteDirectory($request->path);
    }

    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        return $this->service->uploadFile($request->file, $request->current);
    }

    /**
     * @param Request $request
     */
    public function getInfo(Request $request)
    {
        return $this->service->getFileInfo($request->file);
    }

    /**
     * @param Request $request
     */
    public function deleteFile(Request $request)
    {
        return $this->service->deleteFile($request->file);
    }
}
