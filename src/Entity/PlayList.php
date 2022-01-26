<?php

namespace App\Entity;

use App\Repository\PlayListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=PlayListRepository::class)
 * @ORM\Table(name="play_list",
 *     indexes={
 *          @ORM\Index(name="idx_chapter_id", columns={"chapter_id"})
 *     }
 * )
 */
class PlayList
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
     * @ORM\OneToMany(targetEntity=ChapterPlayListSort::class, mappedBy="playList", orphanRemoval="true", cascade={"persist"})
     */
    private $chapterSorts;

    /**
     * @ORM\OneToMany(targetEntity=TrackSort::class, mappedBy="playList", orphanRemoval="true", cascade={"persist"})
     */
    private $trackSorts;

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
        $this->trackSorts = new ArrayCollection();
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

    public function getChapterSorts(): ?ChapterPlayListSort
    {
        return $this->chapterSorts;
    }

    public function setChapterSorts(?ChapterPlayListSort $playListSort): self
    {
        $this->chapterSorts = new ArrayCollection;

        foreach ($playListSort as $ts) {
            $this->addChapterSorts($ts);
        }

        return $this;
    }

    public function addChapterSorts(ChapterPlayListSort $playListSort): self
    {
        if (!$this->chapterSorts->contains($playListSort)) {
            $this->chapterSorts[] = $playListSort;
            $playListSort->setPlayList($this);
        }

        return $this;
    }

    /**
     * @return Collection|TrackSort[]
     */
    public function getTrackSorts(): Collection
    {
        return $this->trackSorts;
    }

    /**
     * @param ArrayCollection $trackSorts
     */
    public function setTrackSorts(ArrayCollection $trackSorts): void
    {
        $this->trackSorts = new ArrayCollection;

        foreach ($trackSorts as $ts) {
            $this->addTrackSort($ts);
        }
    }

    public function addTrackSort(TrackSort $trackSort): self
    {
        if (!$this->trackSorts->contains($trackSort)) {
            $this->trackSorts[] = $trackSort;
            $trackSort->setPlayList($this);
        }

        return $this;
    }

    public function removeTrackSort(TrackSort $trackSort): self
    {
        if ($this->trackSorts->removeElement($trackSort)) {
            // set the owning side to null (unless already changed)
            if ($trackSort->getPlayList() === $this) {
                $trackSort->setPlayList(null);
            }
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
        return 'PlayList [' . $this->getTitle() . ']';
    }
}
