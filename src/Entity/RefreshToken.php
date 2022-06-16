<?php

namespace App\Entity;

use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 255)]
    private $refreshToken;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EAGER', inversedBy: 'refreshTokens')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime')]
    private $expiresAt;

    /**
     * Creates a new model instance based on the provided details.
     */
    public static function createForUserWithTtl(User $user, int $ttl): self
    {
        $model = new static();
        $model->refreshToken = bin2hex(random_bytes(64));
        $model->user = $user;
        $model->expiresAt = new \DateTime('+'.$ttl.' seconds');

        return $model;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt < new \DateTime();
    }

    public function __toString(): string
    {
        return (string)$this->refreshToken;
    }
}
