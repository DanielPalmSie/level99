<?php

namespace App\Entity;

use App\Repository\PlayoffsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayoffsRepository::class)]
class Playoffs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $round;

    #[ORM\ManyToOne(inversedBy: 'playoffs')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $team1;

    #[ORM\ManyToOne(inversedBy: 'playoffs')]
    private Team $team2;

    #[ORM\ManyToOne(inversedBy: 'playoffs')]
    #[ORM\JoinColumn(nullable: false)]
    private Team $winner;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRound(): int
    {
        return $this->round;
    }

    /**
     * @param int $round
     *
     * @return $this
     */
    public function setRound(int $round): self
    {
        $this->round = $round;

        return $this;
    }

    /**
     * @return Team
     */
    public function getTeam1(): Team
    {
        return $this->team1;
    }

    /**
     * @param Team $team1
     *
     * @return $this
     */
    public function setTeam1(Team $team1): self
    {
        $this->team1 = $team1;

        return $this;
    }

    /**
     * @return Team
     */
    public function getTeam2(): Team
    {
        return $this->team2;
    }

    /**
     * @param Team $team2
     *
     * @return $this
     */
    public function setTeam2(Team $team2): self
    {
        $this->team2 = $team2;

        return $this;
    }

    /**
     * @return Team
     */
    public function getWinner(): Team
    {
        return $this->winner;
    }

    /**
     * @param Team $winner
     *
     * @return $this
     */
    public function setWinner(Team $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
