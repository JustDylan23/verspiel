<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CommentSectionTrait;
use App\Entity\Traits\TimestampableTrait;
use App\Repository\ChapterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
#[UniqueEntity(fields: ['number', 'novel'])]
#[ORM\HasLifecycleCallbacks]
class Chapter
{
    use TimestampableTrait;
    use CommentSectionTrait;

    #[ORM\Id] #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer', length: 255)]
    private $number;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $content;

    #[ORM\ManyToOne(targetEntity: Novel::class, inversedBy: 'chapters')]
    #[ORM\JoinColumn(nullable: false)]
    private $novel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getNovel(): ?Novel
    {
        return $this->novel;
    }

    public function setNovel(?Novel $novel): self
    {
        $this->novel = $novel;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->title;
    }
}
