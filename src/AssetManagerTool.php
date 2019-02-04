<?php

namespace Bakerkretzmar\AssetManager;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class AssetManagerTool extends Tool
{
    public function boot()
    {
        Nova::script('nova-asset-manager', __DIR__.'/../dist/js/tool.js');
    }

    public function renderNavigation()
    {
        return view('nova-asset-manager::navigation');
    }
}
