<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $division;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Team $team1 = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Team $team2 = null;

    #[ORM\ManyToOne(inversedBy: 'winner_games')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Team $winner = null;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return $this
     */
    public function setDivision(string $division): self
    {
        $this->division = $division;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam1(): ?Team
    {
        return $this->team1;
    }

    /**
     * @param Team|null $team1
     * @return $this
     */
    public function setTeam1(?Team $team1): self
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getTeam2(): ?Team
    {
        return $this->team2;
    }

    /**
     * @param Team|null $team2
     * @return $this
     */
    public function setTeam2(?Team $team2): self
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * @return Team|null
     */
    public function getWinner(): ?Team
    {
        return $this->winner;
    }

    /**
     * @param Team|null $winner
     *
     * @return $this
     */
    public function setWinner(?Team $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
