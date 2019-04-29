<?php

namespace App\Http\Models;

use App\Http\Collections\PhotosCollection;
use App\Http\Entities\PhotoEntity;
use App\Http\ValueObjects\Url;

class ApplicationModel
{
    public function createAlbum(string $name)
    {

    }

    public function getAlbums()
    {
        $sql = <<<QUERY
select
    album_id,
    title,
from albums
where deleted is null
QUERY;



    }

    public function getPhotosByPage(int $page, int $limit = 50) : array
    {
        $offset = ($page - 1) * $limit;

        $sql = <<<QUERY
select
photo_id, title, url, thumbnail_url, description, author
from photos
order by photo_id asc 
limit :offset, :limit
QUERY;

        // zamiast ponizeszej kwerendy mozna uzyc w poprzedniej SQL_CALC_FOUND_ROWS
        $sqlCount = <<<QUERY
select
count(photo_id) as total
from photos
QUERY;

        $binding = [
          ':limit' => $limit,
          ':offset' => $offset
        ];

        $pc = new PhotosCollection();

        $results = \DB::select($sql, $binding);

        $count = count($results);

        foreach ($results as $photo) {
            $pc->append(new PhotoEntity(
                $photo->photo_id,
                $photo->title,
                new Url($photo->url),
                new Url($photo->thumbnail_url),
                $photo->description,
                $photo->author
            ));
        }

        $pc->setTotal($count);

        $pc->rewind();

        $resultCount = \DB::selectOne($sqlCount, $binding);


        return ['pc' => $pc, 'total' => $resultCount->total];
    }

    public function insertPhoto(PhotosCollection $images)
    {
        \DB::beginTransaction();

        $query = <<<QUERY
insert into photos(photo_id, title, url, thumbnail_url, description, author)
values(:photo_id, :title, :url, :thumbnail_url, :description, :author)
QUERY;

        while ($images->valid()) {
            $image = $images->current();

            $binding = [
                ':photo_id' => $image->getId(),
                ':title' => $image->getTitle(),
                ':url' => $image->getUrl()->getUrl(),
                ':thumbnail_url' => $image->getThumbnailUrl()->getUrl(),
                ':description' => '',
                ':author' => '',
            ];

            \DB::insert($query, $binding);

            $images->next();
        }

        \DB::commit();

        /* pod spodem inna wersja mass insert
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
}*/
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

    public function insertAlbums(PhotosCollection $images)
    {
        $query = <<<QUERY
insert into album(album_id, title)
select * from (select :album_id as alb_id, :title as alb_tt) as tmp
where not exists(
    select album_id from album where album_id = :album_id_2
    )
QUERY;
        $albums = [];

        while ($images->valid()) {
            $image = $images->current();

            $albums[] = [
                ':album_id' => $image->getAlbumId(),
                ':album_id_2' => $image->getAlbumId(),
                ':title' => 'Album ' . $image->getAlbumId(),
            ];

            $images->next();
        }

        foreach ($albums as $album) {
            $binding = $album;

            \DB::insert($query, $binding);

        }
    }

    public function insertImage2AlbumLink(PhotosCollection $images)
    {
        \DB::beginTransaction();

        $query = <<<QUERY
insert into photos_2_album(album_id, photo_id)
select * from (select :album_id as alb_id, :photo_id as pht_id) as tmp
where not exists(
    select album_id, photo_id from photos_2_album where album_id = :album_id_2 and photo_id = :photo_id_2
)
QUERY;


        while ($images->valid()) {
            $image = $images->current();

            $binding = [
                ':photo_id' => $image->getId(),
                ':album_id' => $image->getAlbumId(),
                ':photo_id_2' => $image->getId(),
                ':album_id_2' => $image->getAlbumId(),
            ];

            \DB::insert($query, $binding);

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
