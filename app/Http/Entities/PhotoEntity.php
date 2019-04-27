<?php

namespace App\Http\Entities;

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
     * @var \App\ValueObjects\Url
     */
    protected $url;

    /**
     * @var \App\ValueObjects\Url
     */
    protected $thumbnailUrl;

    /**
     * PhotoEntity constructor.
     * @param int $id
     * @param string $title
     * @param \App\ValueObjects\Url $url
     * @param \App\ValueObjects\Url $thumbnailUrl
     */
    public function __construct(int $id, string $title, \App\ValueObjects\Url $url, \App\ValueObjects\Url $thumbnailUrl)
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
     * @return \App\ValueObjects\Url
     */
    public function getUrl(): \App\ValueObjects\Url
    {
        return $this->url;
    }

    /**
     * @return \App\ValueObjects\Url
     */
    public function getThumbnailUrl(): \App\ValueObjects\Url
    {
        return $this->thumbnailUrl;
    }
}
