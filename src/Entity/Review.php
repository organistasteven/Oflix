<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReviewRepository::class)
 */
class Review
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * ? https://symfony.com/doc/5.4/reference/constraints/Length.html
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 6,
     *      max = 255,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Choice({1, 2, 3, 4, 5})
     */
    private $rating;

    /**
     * @ORM\Column(type="json")
     */
    private $reactions = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $watchedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class)
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReactions(): ?array
    {
        return $this->reactions;
    }

    public function setReactions(array $reactions): self
    {
        $this->reactions = $reactions;

        return $this;
    }

    public function getWatchedAt(): ?\DateTime
    {
        return $this->watchedAt;
    }

    public function setWatchedAt(\DateTime $watchedAt): self
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }
}