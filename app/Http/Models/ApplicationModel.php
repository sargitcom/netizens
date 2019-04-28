<?php

namespace App\Http\Models;

use App\Http\Collections\PhotosCollection;
use App\Http\Entities\PhotoEntity;

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

    public function insertPhoto(PhotosCollection $images)
    {
        \DB::beginTransaction();
        $data = [];

        while ($images->valid()) {
            $image = $images->current();

            $data[] = [
                'photo_id' => $image->getId(),
                'title' => $image->getTitle(),
                'url' => $image->getUrl()->getUrl(),
                'thumbnail_url' => $image->getThumbnailUrl()->getUrl(),
                'description' => '',
                'author' => '',
            ];

            $images->next();
        }

        if (!empty($data)) {
            \DB::table('photos')->insert($data);
        }
        \DB::commit();
    }

    public function updatePhoto(PhotosCollection $images)
    {
        \DB::beginTransaction();

        while ($images->valid()) {
            $image = $images->current();

            $data = [
                'title' => $image->getTitle(),
                'url' => $image->getUrl()->getUrl(),
                'thumbnail_url' => $image->getThumbnailUrl()->getUrl(),
                'description' => '',
                'author' => '',
            ];

            $where = [
                'photo_id' => $image->getId(),
            ];

            \DB::table('photos')
                ->where($where)
                ->update($data);

            $images->next();
        }

        \DB::commit();
    }

    public function getPhotosIds()
    {
        $sql = <<<QUERY
select
    photo_id
from
    photos
order by photo_id asc
QUERY;

        $results = \DB::select($sql, []);

        if (empty($results)) {
            return [];
        }

        $data = [];

        foreach ($results as $result) {
            $data[] = $result->photo_id;
        }

        return $data;
    }
}
