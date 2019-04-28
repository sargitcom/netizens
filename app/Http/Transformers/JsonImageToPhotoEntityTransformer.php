<?php

namespace App\Http\Transformers;

use App\Http\Entities\PhotoEntity;
use App\Http\ValueObjects\Url;

class JsonImageToPhotoEntityTransformer
{
    public static function transformToEntity($image)
    {
        return new PhotoEntity(
            $image->id,
            $image->title,
            new Url($image->url),
            new Url($image->thumbnailUrl)
        );
    }
}
