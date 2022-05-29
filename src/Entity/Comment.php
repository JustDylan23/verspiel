<?php

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Repository\CommentRepository;
use App\Validator as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private $content;

    #[ORM\ManyToOne(targetEntity: CommentSection::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private $commentSection;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'replies')]
    private $replyTo;

    #[ORM\OneToMany(mappedBy: 'replyTo', targetEntity: self::class)]
    private $replies;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[AppAssert\Owner]
    private $author;

    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCommentSection(): ?CommentSection
    {
        return $this->commentSection;
    }

    public function setCommentSection(?CommentSection $commentSection): self
    {
        $this->commentSection = $commentSection;

        return $this;
    }

    public function getReplyTo(): ?self
    {
        return $this->replyTo;
    }

    public function setReplyTo(?self $replyTo): self
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getReplies(): Collection
    {
        return $this->replies;
    }

    public function getReplyCount(): int
    {
        return $this->replyCount;
    }

    public function addReply(self $reply): self
    {
        if (!$this->replies->contains($reply)) {
            $this->replies[] = $reply;
            $reply->setReplyTo($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->replies->removeElement($reply)) {
            // set the owning side to null (unless already changed)
            if ($reply->getReplyTo() === $this) {
                $reply->setReplyTo(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
