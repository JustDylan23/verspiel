<?php

namespace App\Entity;

use App\Entity\Traits\CommentSectionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\NovelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NovelRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Novel
{
    use TimestampableTrait, CommentSectionTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $title;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $shortDescription;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank]
    private $description;

    #[ORM\OneToMany(mappedBy: 'novel', targetEntity: Chapter::class, cascade: ['persist'], orphanRemoval: true)]
    #[ORM\OrderBy(['number' => 'DESC'])]
    private $chapters;

    #[ORM\Column(type: 'boolean')]
    private $featured;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setNovel($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): self
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getNovel() === $this) {
                $chapter->setNovel(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->title;
    }

    public function isFeatured(): ?bool
    {
        return $this->featured;
    }

    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }
}
