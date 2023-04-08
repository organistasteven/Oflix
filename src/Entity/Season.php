<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SeasonRepository::class)
 */
class Season
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *      message="Vous devez donner un nom à la saison"
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 250,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message="Vous devez donner un nombre d'épisodes"
     * )
     */
    private $numberEpisode;

    /**
    * ceci est le owning side de la relation 
    * @ORM\ManyToOne(targetEntity=Movie::class, inversedBy="seasons")
    * @Assert\NotBlank
     */
    private $movie;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNumberEpisode(): ?int
    {
        return $this->numberEpisode;
    }

    public function setNumberEpisode(int $numberEpisode): self
    {
        $this->numberEpisode = $numberEpisode;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(?Movie $Movie): self
    {
        $this->movie = $Movie;

        return $this;
    }
}
