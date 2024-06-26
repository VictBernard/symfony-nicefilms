<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "season", indexes: [
    new ORM\Index(name: "IDX_F0E45BA95278319C", columns: ["series_id"])
])]
#[ORM\Entity]
class Season
{
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(name: "number", type: "integer", nullable: false)]
    private $number;

    #[ORM\ManyToOne(targetEntity: "Series")]
    #[ORM\JoinColumn(name: "series_id", referencedColumnName: "id")]
    private $series;

    #[ORM\OrderBy(["number" => "ASC"])]
    #[ORM\OneToMany(mappedBy: 'season', targetEntity: Episode::class)]
    private Collection $episodes;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSerie(): ?Series
    {
        return $this->series;
    }

    public function setSerie(?Series $series): static
    {
        $this->series = $series;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): static
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setSeasons($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getSeasons() === $this) {
                $episode->setSeasons(null);
            }
        }

        return $this;
    }
}
