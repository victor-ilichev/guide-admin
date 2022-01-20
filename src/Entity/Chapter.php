<?php

namespace App\Entity;

use App\Repository\ChapterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 * @ORM\Table(name="chapter",
 *     indexes={
 *          @ORM\Index(name="idx_excursion_id", columns={"excursion_id"})
 *     }
 * )
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
     * @var Excursion
     *
     * @ORM\ManyToOne(targetEntity=Excursion::class, inversedBy="chapters")
     */
    private $excursion;

    /**
     * @ORM\OneToMany(targetEntity=PlayList::class, mappedBy="chapter")
     */
    private $playLists;

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
        $this->playLists = new ArrayCollection();
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

    public function getExcursion(): Excursion
    {
        return $this->excursion;
    }

    public function setExcursion(Excursion $excursion): void
    {
        $this->excursion = $excursion;
    }

    /**
     * @return Collection|PlayList[]
     */
    public function getPlayLists(): Collection
    {
        return $this->playLists;
    }

    public function addPlayList(PlayList $playList): self
    {
        if (!$this->playLists->contains($playList)) {
            $this->playLists[] = $playList;
            $playList->setChapter($this);
        }

        return $this;
    }

    public function removePlayList(PlayList $playList): self
    {
        if ($this->playLists->removeElement($playList)) {
            // set the owning side to null (unless already changed)
            if ($playList->getChapter() === $this) {
                $playList->setChapter(null);
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
}
