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



    /**
     * Set display in details and list as image or icon.
     *
     * @return $this
     */
    // public function displayAsImage()
    // {
    //     return $this->withMeta(['display' => 'image']);
    // }

    /**
     * Set current folder for the field.
     *
     * @param   string  $folderName
     *
     * @return  $this
     */
    // public function folder($folderName)
    // {
    //     return $this->withMeta(['folder' => $folderName]);
    // }

    /**
     * Resolve the thumbnail URL for the field.
     *
     * @return string|null
     */
    // public function resolveInfo()
    // {
    //     if ($this->value) {
    //         $service = new AssetManagerService();

    //         $data = $service->getFileInfoAsArray($this->value);

    //         if (empty($data)) {
    //             return [];
    //         }

    //         return $this->fixNameLabel($data);
    //     }

    //     return [];
    // }

    /**
     * Get additional meta information to merge with the element payload.
     *
     * @return array
     */
    // public function meta()
    // {
    //     return array_merge($this->resolveInfo(), $this->meta);
    // }

    /**
     * FIx name label.
     *
     * @param array $data
     *
     * @return array
     */
    // private function fixNameLabel(array $data): array
    // {
    //     $data['filename'] = $data['name'];
    //     unset($data['name']);

    //     return $data;
    // }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $model->{$attribute} = json_decode($request[$requestAttribute], true);
        }

    }
}
