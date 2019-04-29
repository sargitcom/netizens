<?php

namespace App\Http\Collections;

use App\Http\Entities\AlbumEntity;

class AlbumsCollection extends Collection
{
    public function current(): AlbumEntity
    {
        return $this->array[$this->position];
    }

    public function append(AlbumEntity $album)
    {
        $this->array[$this->position++] = $album;
    }
}
