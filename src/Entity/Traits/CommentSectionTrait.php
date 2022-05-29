<?php

namespace App\Entity\Traits;

use App\Entity\CommentSection;
use Doctrine\ORM\Mapping as ORM;

trait CommentSectionTrait
{
    #[ORM\OneToOne(targetEntity: CommentSection::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $commentSection;

    #[ORM\PrePersist]
    public function initialiseCommentSection(): void
    {
        if (null === $this->commentSection) {
            $this->commentSection = new CommentSection();
        }
    }

    public function getCommentSection(): ?CommentSection
    {
        return $this->commentSection;
    }

    public function setCommentSection(CommentSection $commentSection): self
    {
        $this->commentSection = $commentSection;

        return $this;
    }
}
