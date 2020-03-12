<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="reply")
     * @ORM\JoinColumn(name="commentOwner", referencedColumnName="id", onDelete="SET NULL")
     */
    private $commentOwner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="commentOwner")
     *
     */
    private $reply;

    public function __construct()
    {
        $this->reply = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedTime(): ?\DateTimeInterface
    {
        return $this->createdTime;
    }

    public function setCreatedTime(\DateTimeInterface $createdTime): self
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    public function getCommentOwner(): ?self
    {
        return $this->commentOwner;
    }

    public function setCommentOwner(?self $commentOwner): self
    {
        $this->commentOwner = $commentOwner;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getReply(): Collection
    {
        return $this->reply;
    }

    public function addReply(self $reply): self
    {
        if (!$this->reply->contains($reply)) {
            $this->reply[] = $reply;
            $reply->setCommentOwner($this);
        }

        return $this;
    }

    public function removeReply(self $reply): self
    {
        if ($this->reply->contains($reply)) {
            $this->reply->removeElement($reply);
            // set the owning side to null (unless already changed)
            if ($reply->getCommentOwner() === $this) {
                $reply->setCommentOwner(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getContent();
    }
}
