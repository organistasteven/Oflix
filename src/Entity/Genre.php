<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_get_collection", "movies_get_item", "genres_get_collection"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"movies_get_collection", "movies_get_item", "genres_get_collection"})
     */
    private $title;

    /**
     * ceci est le owning side de la relation
     * @ORM\ManyToMany(targetEntity=Movie::class, inversedBy="genres")
     */
    private $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        $this->movies->removeElement($movie);

        return $this;
    }
}

