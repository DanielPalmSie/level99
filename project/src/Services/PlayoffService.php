<?php

namespace App\Services;

use App\Entity\Playoffs;
use App\Repository\PlayoffsRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;

class PlayoffService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var TeamRepository
     */
    private TeamRepository $teamRepository;

    /**
     * @var PlayoffsRepository
     */
    private PlayoffsRepository $playoffRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TeamRepository $teamRepository,
        PlayoffsRepository $playoffsRepository
    ) {
        $this->entityManager = $entityManager;
        $this->teamRepository = $teamRepository;
        $this->playoffRepository = $playoffsRepository;
    }

    /**
     * @return void
     */
    public function generateFirstRoundPlayoffs(): void
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Playoffs')->execute();

        // Select the teams with the most points from Division A
        $topTeamsA = $this->findTopTeamsByDivision('A');

        // Select the teams with the highest number of points from Division B
        $topTeamsB = $this->findTopTeamsByDivision('B');

        // Initialization of a round
        $round = 1;

        // Initialize an array of games
        $games = [];

        // Creating games between Division A and Division B teams
        for ($i = 0; $i < count($topTeamsA); $i++) {
            $team1 = $topTeamsA[$i];
            $team2 = $topTeamsB[count($topTeamsB) - $i - 1];

            // Create a game entry in the Playoff table
            $game = new Playoffs();
            $game->setTeam1($team1);
            $game->setTeam2($team2);
            $game->setRound($round);

            // Determining the winner of the game
            if ($team1->getScore() > $team2->getScore()) {
                $game->setWinner($team1);
            } else {
                $game->setWinner($team2);
            }

            // Adding a game to the game array
            $games[] = $game;
        }

        // Saving games to the database
        $entityManager = $this->entityManager;
        foreach ($games as $game) {
            $entityManager->persist($game);
        }
        $entityManager->flush();
    }

    /**
     * @return array
     */
    public function generateRestGamesOfPlayoffs(): array
    {
        // Get a list of all teams that won in the first round of the playoffs
        $teams = $this->teamRepository->findTeamsWhichWonInPlayoff();

        // randomly shuffle the commands
        shuffle($teams);

        $roundNumber = 2;

        // Generate games until there is one winner
        while (count($teams) > 1) {
            // Create a new round
            $round = new Playoffs();
            $round->setRound($roundNumber);

            // Select the first command from the list of commands
            $team1 = array_shift($teams);
            $round->setTeam1($team1);

            // Select the second command from the list of commands
            $team2 = array_shift($teams);
            $round->setTeam2($team2);

            // Determine a random winner
            $winner = rand(1, 2) === 1 ? $team1 : $team2;
            $round->setWinner($winner);

            // add the game to the Playoff table
            $this->entityManager->persist($round);

            // add the winner back to the list of teams for the next round
            $teams[] = $winner;

            // Increase the round number
            $roundNumber++;
        }

        $this->entityManager->flush();

        return $this->playoffRepository->getPlayoffRecords();
    }

    /**
     * @param string $division
     *
     * @return array
     */
    public function findTopTeamsByDivision(string $division): array
    {
        $topTeams = $this->teamRepository->findTopTeamsByDivision($division);
        return $this->teamRepository->findTeamsByIds($topTeams);
    }

    /**
     * @return Playoffs[]
     */
    public function getAllGames(): array
    {
        return $this->playoffRepository->findAll();
    }
}

