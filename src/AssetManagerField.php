<?php

namespace Bakerkretzmar\AssetManager;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class AssetManagerField extends Field
{
    public $component = 'asset-manager-field';

    public $showOnIndex = false;

    public function disk(string $disk)
    {
        return $this->withMeta(['disk' => $disk]);
    }

    public function filetypes(array $types)
    {
        return $this->withMeta(['filetypes' => $types]);
    }

    public function folder(string $dir)
    {
        return $this->withMeta(['folder' => $dir]);
    }

    public function maximum(int $max)
    {
        return $this->withMeta(['maximum' => $max]);
    }

    public function navigable()
    {
        return $this->withMeta(['navigable' => true]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }

    }
}
