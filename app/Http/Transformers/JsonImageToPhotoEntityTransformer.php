<?php

namespace App\Http\Transformers;

use App\Http\Entities\ExtendedPhotoEntity;
use App\Http\ValueObjects\Url;

class JsonImageToPhotoEntityTransformer
{
    public static function transformToEntity($image)
    {
        return new ExtendedPhotoEntity(
            $image->id,
            $image->title,
            new Url($image->url),
            new Url($image->thumbnailUrl),
            '',
            '',
            $image->albumId
        );
    }
}
