<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use App\Service\MySlugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
// Groupes de sérialisation
// @see https://symfony.com/doc/5.4/components/serializer.html#attributes-groups
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * 
 * Une annotation par propriété
 * @UniqueEntity("title")
 * @UniqueEntity("slug")
 */
class Movie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(min=1)
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $duration;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups("movies_get_collection")
     */
    private $summary;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({"movies_get_item"})
     */
    private $synopsys;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $rating;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Groups({"movies_get_item"})
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $type;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Url
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $poster;

    /**
     * ceci est le inverse side de la relation
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="movie", cascade={"remove"})
     * @Groups({"movies_get_item"})
     */
    private $seasons;

    /**
     * ceci est le inverse side de la relation
     * @ORM\ManyToMany(targetEntity=Genre::class, mappedBy="movies")
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $genres;

    /**
     * @ORM\OneToMany(targetEntity=Casting::class, mappedBy="movie", cascade={"remove"})
     * @Groups({"movies_get_item"})
     */
    private $castings;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="movie", cascade={"remove"})
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     * @Groups({"movies_get_collection", "movies_get_item"})
     */
    private $slug;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->castings = new ArrayCollection();
        $this->reviews = new ArrayCollection();
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

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getSynopsys(): ?string
    {
        return $this->synopsys;
    }

    public function setSynopsys(string $synopsys): self
    {
        $this->synopsys = $synopsys;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons[] = $season;
            $season->setMovie($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getMovie() === $this) {
                $season->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
            $genre->addMovie($this);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->removeElement($genre)) {
            $genre->removeMovie($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Casting>
     */
    public function getCastings(): Collection
    {
        return $this->castings;
    }

    public function addCasting(Casting $casting): self
    {
        if (!$this->castings->contains($casting)) {
            $this->castings[] = $casting;
            $casting->setMovie($this);
        }

        return $this;
    }

    public function removeCasting(Casting $casting): self
    {
        if ($this->castings->removeElement($casting)) {
            // set the owning side to null (unless already changed)
            if ($casting->getMovie() === $this) {
                $casting->setMovie(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }
}
