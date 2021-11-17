<?php

namespace R64\NovaImageCropper;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Image;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Http\Requests\NovaRequest;

class ImageCropperSignature extends Image
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-image-cropper';

    public $disk;

    public $path;

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

        $this->preview(function () use ($attribute){
            if (!$this->value) {
                return null;
            }

            $url = Storage::disk($this->disk)->url($this->value);

            $path_info = pathinfo($url);

            $filetype = 'jpg';

            if (array_key_exists('extension', $path_info)) {
                $filetype = $path_info['extension'];
            }

            try {
                $encoded_file = base64_encode(file_get_contents($url));
            } catch (\Exception $e) {
                return '';
            }

            return 'data:image/' . $filetype . ';base64,' . $encoded_file;
        });
    }

    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set the name of the disk the file is stored on by default.
     *
     * @param  string  $disk
     * @return $this
     */
    public function disk($disk)
    {
        $this->disk = $disk;

        return $this;
    }

    public function fillAttribute(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $requestAttribute = "___upload-".$requestAttribute;

        $old_image = $model->{$attribute};

        if (is_null($file = $request->file($requestAttribute)) || ! $file->isValid()) {
            return;
        }

        $model->{$attribute} = $request->file($requestAttribute)->store($this->path, $this->disk);

        if($model->{$attribute} !== $old_image){
            Storage::disk($this->disk)->delete($old_image);
        };
    }

    public function avatar()
    {
        return $this->withMeta(['isAvatar' => true]);
    }

    public function aspectRatio($ratio)
    {
        return $this->withMeta(['aspectRatio' => $ratio]);
    }

    public function width($width)
    {
        return $this->withMeta(['width' => $width]);
    }
}
