<?php

namespace App\Http\Entities;

class AlbumEntity
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
     * @var \DateTime
     */
    protected $added;

    /**
     * @var \DateTime|null
     */
    protected $deleted;

    /**
     * AlbumEntity constructor.
     * @param int $id
     * @param string $title
     * @param DateTime $added
     * @param DateTime|null $deleted
     */
    public function __construct(int $id, string $title, \DateTime $added, ?\DateTime $deleted)
    {
        $this->id = $id;
        $this->title = $title;
        $this->added = $added;
        $this->deleted = $deleted;
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
     * @return DateTime
     */
    public function getAdded(): DateTime
    {
        return $this->added;
    }

    /**
     * @return DateTime|null
     */
    public function getDeleted(): ?DateTime
    {
        return $this->deleted;
    }
}
