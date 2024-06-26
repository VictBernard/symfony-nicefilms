<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "episode", indexes: [
    new ORM\Index(name: "IDX_DDAA1CDA4EC001D1", columns: ["season_id"])
])]
#[ORM\Entity]
class Episode
{
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(name: "title", type: "string", length: 128, nullable: false)]
    private $title;

    #[ORM\Column(name: "date", type: "date", nullable: true)]
    private $date;

    #[ORM\Column(name: "imdb", type: "string", length: 128, nullable: false)]
    private $imdb;

    #[ORM\Column(name: "imdbrating", type: "float", precision: 10, scale: 0, nullable: true)]
    private $imdbrating;

    #[ORM\Column(name: "number", type: "integer", nullable: false)]
    private $number;

    #[ORM\ManyToMany(targetEntity: "User", mappedBy: "episode")]
    private $user = array();

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    private ?Season $season = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getImdb(): ?string
    {
        return $this->imdb;
    }

    public function setImdb(string $imdb): self
    {
        $this->imdb = $imdb;

        return $this;
    }

    public function getImdbrating(): ?float
    {
        return $this->imdbrating;
    }

    public function setImdbrating(?float $imdbrating): self
    {
        $this->imdbrating = $imdbrating;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->addEpisode($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->removeElement($user)) {
            $user->removeEpisode($this);
        }

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSeasons(): ?Season
    {
        return $this->season;
    }

    public function setSeasons(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }
}
