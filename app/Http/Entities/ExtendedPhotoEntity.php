<?php

namespace App\Http\Entities;

use App\Http\ValueObjects\Url;

class ExtendedPhotoEntity extends PhotoEntity
{
    /**
     * @var int
     */
    protected $albumId;

    /**
     * ExtendedPhotoEntity constructor.
     * @param int $id
     * @param string $title
     * @param Url $url
     * @param Url $thumbnailUrl
     * @param string $description
     * @param string $author
     * @param int $albumId
     */
    public function __construct(int $id, string $title, Url $url, Url $thumbnailUrl, string $description, string $author, int $albumId)
    {
        $this->albumId = $albumId;

        parent::__construct($id, $title, $url, $thumbnailUrl, $description, $author);
    }

    /**
     * @return int
     */
    public function getAlbumId(): int
    {
        return $this->albumId;
    }
}
