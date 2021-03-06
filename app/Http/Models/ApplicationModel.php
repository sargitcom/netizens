<?php

namespace App\Http\Models;

use App\Exceptions\NoPhotoExistsException;
use App\Http\Collections\AlbumsCollection;
use App\Http\Collections\PhotosCollection;
use App\Http\Entities\AlbumEntity;
use App\Http\Entities\PhotoEntity;
use App\Http\ValueObjects\Url;

class ApplicationModel
{
    const LIMIT_NONE = null;

    public function addImage2Album(int $albumId, int $imageId)
    {
        $query = <<<QUERY
insert into photos_2_album(album_id, photo_id)
select * from (select :album_id as alb_id, :photo_id as pht_id) as tmp
where not exists(
    select album_id, photo_id from photos_2_album where album_id = :album_id_2 and photo_id = :photo_id_2
)
QUERY;

            $binding = [
                ':photo_id' => $imageId,
                ':album_id' => $albumId,
                ':photo_id_2' => $imageId,
                ':album_id_2' => $albumId,
            ];

            \DB::insert($query, $binding);
    }

    public function getAlbumById(int $id)
    {
        $sql = <<<QUERY
select
    album_id,
    title,
    added,
    deleted
from album
where album_id = :album_id
QUERY;

        $album = \DB::selectOne($sql, [':album_id' => $id]);

        if (empty($album)) {
            throw new \Exception('To nie jest nazwany exception');
        }

        return new AlbumEntity(
            $album->album_id,
            $album->title,
            new \DateTime($album->added),
            $album->deleted === null ? null : new \DateTime($album->deleted)
        );
    }

    public function getAlbums() : AlbumsCollection
    {
        $sql = <<<QUERY
select
    album_id,
    title,
    added,
    deleted
from album
where deleted is null
QUERY;

        $results = \DB::select($sql, []);

        $ac = new AlbumsCollection();

        $count = count($results);

        foreach ($results as $album) {
            $ac->append(new AlbumEntity(
                $album->album_id,
                $album->title,
                new \DateTime($album->added),
                $album->deleted === null ? null : new \DateTime($album->deleted)
            ));
        }

        $ac->setTotal($count);

        $ac->rewind();

        return $ac;
    }

    public function getPhotosByPage(int $page, ?int $limit = 50) : array
    {
        if ($limit !== self::LIMIT_NONE) {
            $offset = ($page - 1) * $limit;

            $sql = <<<QUERY
select
photo_id, title, url, thumbnail_url, description, author
from photos
order by photo_id asc 
limit :offset, :limit
QUERY;

            $binding = [
                ':limit' => $limit,
                ':offset' => $offset
            ];

            $results = \DB::select($sql, $binding);
        } else {
            $offset = ($page - 1) * $limit;

            $sql = <<<QUERY
select
photo_id, title, url, thumbnail_url, description, author
from photos
order by photo_id asc
QUERY;

            $binding = [];

            $results = \DB::select($sql, $binding);
        }


        // zamiast ponizeszej kwerendy mozna uzyc w poprzedniej SQL_CALC_FOUND_ROWS
        $sqlCount = <<<QUERY
select
count(photo_id) as total
from photos
QUERY;

        $pc = new PhotosCollection();

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

    public function getAlbumPhotosById(int $albumId) : array
    {
            $sql = <<<QUERY
select
p.photo_id, title, url, thumbnail_url, description, author
from photos as p
join photos_2_album as p2a on p.photo_id = p2a.photo_id
where p2a.album_id = :album_id
order by p.photo_id asc
QUERY;

        $binding = [
            ':album_id' => $albumId
        ];

        $results = \DB::select($sql, $binding);

        // zamiast ponizeszej kwerendy mozna uzyc w poprzedniej SQL_CALC_FOUND_ROWS
        $sqlCount = <<<QUERY
select
count(p.photo_id) as total
from photos as p
join photos_2_album as p2a on p.photo_id = p2a.photo_id
where p2a.album_id = :album_id
QUERY;

        $pc = new PhotosCollection();

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

    public function getPhotoById(int $id) : PhotoEntity
    {
        $sql = <<<QUERY
select
photo_id, title, url, thumbnail_url, description, author
from photos
where photo_id = :photo_id
QUERY;

        $binding = [
            ':photo_id' => $id,
        ];

        $photo = \DB::selectOne($sql, $binding);

        if (empty($photo)) {
            throw new NoPhotoExistsException();
        }

        return new PhotoEntity(
            $photo->photo_id,
            $photo->title,
            new Url($photo->url),
            new Url($photo->thumbnail_url),
            $photo->description,
            $photo->author
        );
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

    public function updatePhotoById($id, $title, $description, $author)
    {
        $sql = <<<QUERY
update photos
set 
    title = :title, 
    description = :description, 
    author = :author
where
    photo_id = :photo_id
QUERY;

        $binding = [
            'title' => $title,
            'description' => $description,
            'author' => $author,
            'photo_id' => $id
        ];

        \DB::update($sql, $binding);
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
