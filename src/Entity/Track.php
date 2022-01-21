<?php

namespace App\Entity;

use App\Repository\TrackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=TrackRepository::class)
 * @ORM\Table(name="track"
 * )
 */
class Track
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
     * @ORM\Column(type="smallint")
     */
    private $sort;

    /**
     * @ORM\OneToMany(targetEntity=TrackSort::class, mappedBy="track", cascade={"persist"})
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

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|TrackSort[]
     */
    public function getTrackSorts(): Collection
    {
        return $this->trackSorts;
    }

    public function setTrackSorts(ArrayCollection $trackSorts): void
    {
        $this->trackSorts = $trackSorts;
    }

//    public function addTrackSort(TrackSort $trackSort): self
//    {
//        if (!$this->trackSorts->contains($trackSort)) {
//            $this->trackSorts[] = $trackSort;
//            $trackSort->setTrack($this);
//        }
//
//        return $this;
//    }
//
//    public function removeTrackSort(TrackSort $trackSort): self
//    {
//        if ($this->trackSorts->removeElement($trackSort)) {
//            // set the owning side to null (unless already changed)
//            if ($trackSort->getTrack() === $this) {
//                $trackSort->setTrack(null);
//            }
//        }
//
//        return $this;
//    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
