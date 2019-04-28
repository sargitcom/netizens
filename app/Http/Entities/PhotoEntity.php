<?php

namespace App\Http\Entities;

use App\Http\ValueObjects\Url;

class PhotoEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var Url
     */
    protected $thumbnailUrl;

    /**
     * PhotoEntity constructor.
     * @param int $id
     * @param string $title
     * @param Url $url
     * @param Url $thumbnailUrl
     */
    public function __construct(int $id, string $title, Url $url, Url $thumbnailUrl)
    {
        $this->id = $id;
        $this->title = $title;
        $this->url = $url;
        $this->thumbnailUrl = $thumbnailUrl;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Url
     */
    public function getUrl(): Url
    {
        return $this->url;
    }

    /**
     * @return Url
     */
    public function getThumbnailUrl(): Url
    {
        return $this->thumbnailUrl;
    }
}
