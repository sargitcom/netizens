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
     * PhotoEntity constructor.
     * @param int $id
     * @param string $title
     * @param Url $url
     * @param Url $thumbnailUrl
     */
    public function __construct(int $id, string $title, Url $url, Url $thumbnailUrl, int $albumId)
    {
        $this->albumId = $albumId;

        parent::__construct($id, $title, $url, $thumbnailUrl);
    }

    /**
     * @return int
     */
    public function getAlbumId(): int
    {
        return $this->albumId;
    }
}
