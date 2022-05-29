<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TimestampableTrait
{
    use CreatedAtTrait;

    #[ORM\Column(type: 'datetime')]
    protected $createdAt;

    #[ORM\Column(type: 'datetime')]
    protected $updatedAt;

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime());
        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTime());
        }
    }
}
