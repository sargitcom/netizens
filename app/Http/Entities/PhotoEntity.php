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
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $author;

    /**
     * PhotoEntity constructor.
     * @param int $id
     * @param string $title
     * @param Url $url
     * @param Url $thumbnailUrl
     * @param string $description
     * @param string $author
     */
    public function __construct(int $id, string $title, Url $url, Url $thumbnailUrl, string $description = '', string $author = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->url = $url;
        $this->thumbnailUrl = $thumbnailUrl;
        $this->description = $description;
        $this->author = $author;
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

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
