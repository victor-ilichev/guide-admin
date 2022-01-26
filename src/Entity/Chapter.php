<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 * @ORM\Table(name="chapter")
 */
class Chapter
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=ExcursionChapterSort::class, mappedBy="chapter", cascade={"persist"})
     */
    private $sorts;

    /**
     * @ORM\OneToMany(targetEntity=ChapterPlayListSort::class, mappedBy="chapterPl", cascade={"persist"})
     */
    private $playListSorts;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->sorts = new ArrayCollection();
        $this->playListSorts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|ExcursionChapterSort[]
     */
    public function getSorts(): Collection
    {
        return $this->sorts;
    }

    /**
     * @param ArrayCollection $sorts
     */
    public function setSorts(ArrayCollection $sorts): void
    {
        $this->sorts = new ArrayCollection;

        foreach ($sorts as $ts) {
            $this->addSort($ts);
        }
    }

    public function addSort(ExcursionChapterSort $trackSort): self
    {
        if (!$this->sorts->contains($trackSort)) {
            $this->sorts[] = $trackSort;
            $trackSort->setChapter($this);
        }

        return $this;
    }

    /**
     * @return Collection|ExcursionChapterSort[]
     */
    public function getPlayListSorts(): Collection
    {
        return $this->playListSorts;
    }

    /**
     * @param ArrayCollection $sorts
     */
    public function setPlayListSorts(ArrayCollection $sorts): void
    {
        $this->playListSorts = new ArrayCollection;

        foreach ($sorts as $ts) {
            $this->addPlayListSort($ts);
        }
    }

    public function addPlayListSort(ChapterPlayListSort $trackSort): self
    {
        if (!$this->playListSorts->contains($trackSort)) {
            $this->playListSorts[] = $trackSort;
            $trackSort->setChapter($this);
        }

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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function __toString(): string
    {
        return 'Chapter [' . $this->getTitle() . ']';
    }
}
