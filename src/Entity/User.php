<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PharIo\Manifest\ContainsElement;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: "user", uniqueConstraints: [
    new ORM\UniqueConstraint(name: "UNIQ_8D93D649E7927C74", columns: ["email"]),
    new ORM\UniqueConstraint(name: "IDX_8D93D649F92F3E70", columns: ["country_id"]),
])]
#[ORM\Entity]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    #[ORM\Column(name: "name", type: "string", length: 128, nullable: false)]
    private $name;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column(name: "email", type: "string", length: 128, nullable: false)]
    private $email;

    #[ORM\Column(name: "password", type: "string", length: 128, nullable: false)]
    private $password;

    #[ORM\Column(name: "register_date", type: "datetime", nullable: true)]
    private $registerDate;

    #[ORM\Column(name: "admin", type: "boolean", nullable: false)]
    private $admin = '0';

    #[ORM\Column(name: "user_id", type: "string", length: 128, nullable: true)]
    private $userId;

    #[ORM\ManyToOne(targetEntity: "Country")]
    #[ORM\JoinColumn(name: "country_id", referencedColumnName: "id")]
    private $country;

    #[ORM\ManyToMany(targetEntity: "Series", inversedBy: "genre")]
    #[ORM\JoinTable(
        name: "user_series",
        joinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "series_id", referencedColumnName: "id")]
    )]
    private $series = array();

    #[ORM\ManyToMany(targetEntity: "Episode", inversedBy: "genre")]
    #[ORM\JoinTable(
        name: "user_episode",
        joinColumns: [new ORM\JoinColumn(name: "user_id", referencedColumnName: "id")],
        inverseJoinColumns: [new ORM\JoinColumn(name: "episode_id", referencedColumnName: "id")]
    )]
    private $episode = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->series = new \Doctrine\Common\Collections\ArrayCollection();
        $this->episode = new \Doctrine\Common\Collections\ArrayCollection();
        $this->registerDate = new \DateTime();
        $propositionSuivreSerie = array();
        $this->roles = ["ROLE_USER"];
    }

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    public function setRegisterDate($registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Series>
     */
    public function getSeries(): Collection
    {
        return $this->series;
    }

    public function addSeries(Series $series): self
    {
        if (!$this->series->contains($series)) {
            $this->series->add($series);
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function removeSeries(Series $series): self
    {
        $this->series->removeElement($series);

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisode(): Collection
    {
        return $this->episode;
    }

    public function addEpisode(Episode $episode): self
    {
        if (!$this->episode->contains($episode)) {
            $this->episode->add($episode);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): self
    {
        $this->episode->removeElement($episode);

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /** retourne vrai si l'episode e à été vu par l'utilisateur */
    public function watched(Episode $e)
    {
        if($this->episode->contains($e)) {
            return true;
        } else {
            return false;
        }
    }

    public function watchedAllSeason(Season $s): bool
    {
        $allWatched = true;
        foreach($s->getEpisodes() as $e) {
            if(!$this->watched($e)) {
                $allWatched = false;
            }
        }
        return $allWatched;
    }

    public function watchedAllSerie(Series $serie): bool
    {
        $allWatched = true;

        foreach($serie->getSeasons() as $s) {
            foreach($s->getEpisodes() as $e) {
                if(!$this->watched($e)) {
                    $allWatched = false;
                }
            }
        }
        return $allWatched;
    }

<<<<<<< HEAD
    public function notWatchedPreviousEpisodes(Episode $ep): Collection
    {
        $serie = $ep->getSeasons()->getSerie();
        $episodesNotWatched = new ArrayCollection();

        foreach ($serie->getSeasons() as $s) {
            foreach($s->getEpisodes() as $e) {
                // Comparaison des saisons et des numéros d'épisode
                if ($s->getNumber() < $ep->getSeasons()->getNumber() ||
                    ($s->getNumber() == $ep->getSeasons()->getNumber() && $e->getNumber() < $ep->getNumber()) && !$this->watched($e)) {
                    $episodesNotWatched->add($e);
                }
            }
        }

        return $episodesNotWatched;
    }
=======
>>>>>>> adedb8baf (push before merging main into our branch)
}
