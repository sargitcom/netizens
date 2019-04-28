<?php

namespace App\Http\Collections;

use App\Http\Entities\PhotoEntity;

class PhotosCollection extends Collection
{
    public function current(): PhotoEntity
    {
        return $this->array[$this->position];
    }

    public function append(PhotoEntity $photo)
    {
        $this->array[$this->position++] = $photo;
    }
}
