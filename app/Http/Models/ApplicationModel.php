<?php

namespace App\Http\Models;

class ApplicationModel
{
    public function createAlbum(string $name)
    {

    }

    public function getAlbums()
    {
        $sql = <<<QUERY
select

from albums
where deleted is null
QUERY;

    }
}
