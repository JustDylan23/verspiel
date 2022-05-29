<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAtTrait
{
    #[ORM\Column(type: 'datetime')]
    protected $createdAt;

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
