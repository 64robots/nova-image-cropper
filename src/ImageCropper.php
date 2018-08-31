<?php

namespace R64\NovaImageCropper;

use Laravel\Nova\Fields\Image;
use Illuminate\Support\Facades\Storage;

class ImageCropper extends Image
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-image-cropper';

    /**
     * Indicates if the element should be shown on the index view.
     *
     * @var bool
     */
    public $showOnIndex = false;

    /**
     * Create a new field.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  string|null  $disk
     * @param  callable|null  $storageCallback
     * @return void
     */
    public function __construct($name, $attribute = null, $disk = 'public', $storageCallback = null)
    {
        parent::__construct($name, $attribute, $disk, $storageCallback);

        $this->preview(function () {
            if (!$this->value) return null;

            $url = Storage::disk($this->disk)->url($this->value);
            $filetype = pathinfo($url)['extension'];
            return 'data:image/' . $filetype . ';base64,' . base64_encode(file_get_contents($url));
        });
    }
}
