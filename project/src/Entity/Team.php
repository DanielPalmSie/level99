<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $division;

    #[ORM\Column]
    private int $score = 0;

    #[ORM\OneToMany(mappedBy: 'id', targetEntity: Playoffs::class, orphanRemoval: true)]
    private Collection $playoffs;

    #[ORM\OneToMany(mappedBy: 'winner', targetEntity: Game::class, cascade: ['persist', 'remove'])]
    private Collection $winner_games;

    public function __construct()
    {
        $this->playoffs = new ArrayCollection();
        $this->winner_games = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDivision(): string
    {
        return $this->division;
    }

    /**
     * @param string $division
     *
     * @return $this
     */
    public function setDivision(string $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     *
     * @return $this
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, Playoffs>
     */
    public function getPlayoffs(): Collection
    {
        return $this->playoffs;
    }

    /**
     * @param Playoffs $playoff
     *
     * @return $this
     */
    public function addPlayoff(Playoffs $playoff): self
    {
        if (!$this->playoffs->contains($playoff)) {
            $this->playoffs->add($playoff);
            $playoff->setTeam1($this);
        }

        return $this;
    }

    /**
     * @param Playoffs $playoff
     *
     * @return $this
     */
    public function removePlayoff(Playoffs $playoff): self
    {
        if ($this->playoffs->removeElement($playoff)) {
            // set the owning side to null (unless already changed)
            if ($playoff->getTeam1() === $this) {
                $playoff->setTeam1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getWinnerGames(): Collection
    {
        return $this->winner_games;
    }

    /**
     * @param Game $winnerGame
     *
     * @return $this
     */
    public function addWinnerGame(Game $winnerGame): self
    {
        if (!$this->winner_games->contains($winnerGame)) {
            $this->winner_games->add($winnerGame);
            $winnerGame->setWinner($this);
        }

        return $this;
    }

    /**
     * @param Game $winnerGame
     *
     * @return $this
     */
    public function removeWinnerGame(Game $winnerGame): self
    {
        if ($this->winner_games->removeElement($winnerGame)) {
            // set the owning side to null (unless already changed)
            if ($winnerGame->getWinner() === $this) {
                $winnerGame->setWinner(null);
            }
        }

        return $this;
    }
}
