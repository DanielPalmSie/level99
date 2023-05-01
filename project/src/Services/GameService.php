<?php

namespace App\Services;

use App\Entity\Game;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function generateGames(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Game')->execute();
        // Get all the teams from Division A and B
        $teams = $this->entityManager->getRepository(Team::class)->findBy(['division' => ['A', 'B']]);

        // Create game records for each team against every other team in its division
        foreach ($teams as $team1) {
            foreach ($teams as $team2) {
                if ($team1 !== $team2 && $team1->getDivision() === $team2->getDivision()) {
                    // Create a new game
                    $game = new Game();
                    $game->setTeam1($team1);
                    $game->setTeam2($team2);
                    $game->setDivision($team1->getDivision());

                    // Determine the winner and record points for the winning team
                    if (rand(0, 1) === 0) {
                        $game->setWinner($team1);
                        $team1->setScore($team1->getScore() + 1);
                    } else {
                        $game->setWinner($team2);
                        $team2->setScore($team2->getScore() + 1);
                    }

                    // Save the game and the updated data of the winning team
                    $this->entityManager->persist($game);
                    $this->entityManager->persist($game->getWinner());
                }
            }
        }


        $this->entityManager->flush();
    }

    /**
     * @return array[]
     */
    public function getAllGamesByDivision(): array
    {
        // get all the games from the Games table
        $games = $this->entityManager->getRepository(Game::class)->findAll();

        // create arrays for each division
        $divisionA = [];
        $divisionB = [];

        // go through all the games and add them to the appropriate array
        foreach ($games as $game) {
            if ($game->getDivision() === 'A') {
                $divisionA[] = [
                    'id' => $game->getId(),
                    'team_1' => $game->getTeam1()->getId(),
                    'team_2' => $game->getTeam2()->getId(),
                    'winner_id' => $game->getWinner()->getId(),
                    'division' => $game->getDivision(),
                ];
            } elseif ($game->getDivision() === 'B') {
                $divisionB[] = [
                    'id' => $game->getId(),
                    'team_1' => $game->getTeam1()->getId(),
                    'team_2' => $game->getTeam2()->getId(),
                    'winner_id' => $game->getWinner()->getId(),
                    'division' => $game->getDivision(),
                ];
            }
        }

        return [
            'divisionA' => $divisionA,
            'divisionB' => $divisionB,
        ];
    }
}
