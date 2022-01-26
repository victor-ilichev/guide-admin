<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class ChapterPlayListSort
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="playListSorts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chapterPl;

    /**
     * @ORM\ManyToOne(targetEntity=PlayList::class, inversedBy="chapterSorts", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $playList;

    /**
     * @ORM\Column(type="smallint")
     */
    private $sort;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayList(): ?PlayList
    {
        return $this->playList;
    }

    public function setPlayList(?PlayList $playList): self
    {
        $this->playList = $playList;

        return $this;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapterPl;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapterPl = $chapter;

        return $this;
    }

    public function getSort(): ?int
    {
        return $this->sort;
    }

    public function setSort(int $sort): self
    {
        $this->sort = $sort;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function __toString(): string
    {
        return 'ChPlSort [ ... ]';
    }
}
