<?php

namespace App\Domain\Forum\Entity;

use App\Domain\Auth\User;
use App\Http\Twig\CacheExtension\CacheableInterface;
use App\Infrastructure\Spam\SpammableInterface;
use App\Infrastructure\Spam\SpamTrait;
use Doctrine\ORM\Mapping as ORM;
use Parsedown;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Domain\Forum\Repository\MessageRepository")
 * @ORM\Table(name="forum_message")
 */
class Message implements SpammableInterface, CacheableInterface
{
    use SpamTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"read:message"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Forum\Entity\Topic", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Topic $topic;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Auth\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $author;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $accepted = false;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     * @Groups({"read:message", "update:message"})
     */
    private ?string $content = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    private bool $notification = true;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTopic(): Topic
    {
        return $this->topic;
    }

    public function setTopic(Topic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function isAccepted(): ?bool
    {
        return $this->accepted;
    }

    public function setAccepted(bool $accepted): self
    {
        $this->accepted = $accepted;

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

    /**
     * @Groups({"read:message"})
     */
    public function getFormattedContent(): ?string
    {
        return (new Parsedown())
            ->setSafeMode(true)
            ->setBreaksEnabled(true)
            ->text($this->content);
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function hasNotification(): bool
    {
        return $this->notification;
    }

    public function setNotification(bool $notification): Message
    {
        $this->notification = $notification;

        return $this;
    }
}
