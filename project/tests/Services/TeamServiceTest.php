<?php

namespace Tests\Services;

use App\Entity\Game;
use App\Entity\Playoffs;
use App\Entity\Team;
use App\Services\GameService;
use App\Services\PlayoffService;
use App\Services\TeamService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TeamServiceTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PlayoffService
     */
    private $playoffService;


    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $teamRepository = $this->entityManager->getRepository(Team::class);
        $playoffsRepository = $this->entityManager->getRepository(Playoffs::class);
        $this->playoffService = new PlayoffService($this->entityManager, $teamRepository, $playoffsRepository);
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }

    public function testGenerateTeamsTable()
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Playoffs')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Game')->execute();
        $this->entityManager->createQuery('DELETE FROM App\Entity\Team')->execute();

        // create team service instance
        $teamService = new TeamService($this->entityManager);

        // generate teams table
        $teamsTable = $teamService->generateTeamsTable();

        // assert that each division contains 8 teams
        $this->assertCount(8, $teamsTable['DivisionA']);
        $this->assertCount(8, $teamsTable['DivisionB']);

        // assert that teams were saved to database
        $divisionATeams = $this->entityManager->getRepository(Team::class)->findBy(['division' => 'A']);
        $divisionBTeams = $this->entityManager->getRepository(Team::class)->findBy(['division' => 'B']);
        $this->assertCount(8, $divisionATeams);
        $this->assertCount(8, $divisionBTeams);

        $this->entityManager->createQuery('DELETE FROM App\Entity\Team')->execute();

        // assert that expected teams were saved to database for Division A
        $expectedDivisionATeams = [

            (new Team())->setName('A')->setDivision('A')->setScore(0),
            (new Team())->setName('B')->setDivision('A')->setScore(0),
            (new Team())->setName('C')->setDivision('A')->setScore(0),
            (new Team())->setName('D')->setDivision('A')->setScore(0),
            (new Team())->setName('E')->setDivision('A')->setScore(0),
            (new Team())->setName('F')->setDivision('A')->setScore(0),
            (new Team())->setName('G')->setDivision('A')->setScore(0),
            (new Team())->setName('H')->setDivision('A')->setScore(0),
        ];

        foreach ($expectedDivisionATeams as $team) {
            $this->entityManager->persist($team);
        }
        $this->entityManager->flush();


        // assert that expected teams were saved to database for Division B
        $expectedDivisionBTeams = [
            (new Team())->setName('I')->setDivision('B')->setScore(0),
            (new Team())->setName('J')->setDivision('B')->setScore(0),
            (new Team())->setName('K')->setDivision('B')->setScore(0),
            (new Team())->setName('L')->setDivision('B')->setScore(0),
            (new Team())->setName('M')->setDivision('B')->setScore(0),
            (new Team())->setName('N')->setDivision('B')->setScore(0),
            (new Team())->setName('O')->setDivision('B')->setScore(0),
            (new Team())->setName('P')->setDivision('B')->setScore(0),
        ];
        foreach ($expectedDivisionBTeams as $team) {
            $this->entityManager->persist($team);
        }
        $this->entityManager->flush();
    }

    /**
     * @depends testGenerateTeamsTable
     *
     * @return void
     */
    public function testGenerateGames()
    {
        $gameService = new GameService($this->entityManager);

        // Add 8 teams to Division A and 8 teams to Division B

        $teamsA = $this->entityManager->getRepository(Team::class)->findBy(['division' => 'A']);
        $teamsB = $this->entityManager->getRepository(Team::class)->findBy(['division' => 'B']);

        // Create games for each team with every other team in their division
        $gameService->generateGames();

        // Check that the Games table has created game entries for each team with every other team in its division
        foreach ($teamsA as $team1) {
            foreach ($teamsA as $team2) {
                if ($team1 !== $team2) {
                    $games = $this->entityManager->getRepository(Game::class)->findBy([
                        'team1' => $team1,
                        'team2' => $team2,
                        'division' => 'A',
                    ]);
                    $this->assertCount(1, $games);
                }
            }
        }

        foreach ($teamsB as $team1) {
            foreach ($teamsB as $team2) {
                if ($team1 !== $team2) {
                    $games = $this->entityManager->getRepository(Game::class)->findBy([
                        'team1' => $team1,
                        'team2' => $team2,
                        'division' => 'B',
                    ]);
                    $this->assertCount(1, $games);
                }
            }
        }

        // Check that the Games table created 56 games for division A and 56 games for division B
        $gamesA = $this->entityManager->getRepository(Game::class)->findBy(['division' => 'A']);
        $this->assertCount(56, $gamesA);

        $gamesB = $this->entityManager->getRepository(Game::class)->findBy(['division' => 'B']);
        $this->assertCount(56, $gamesB);
    }

    /**
     * @depends testGenerateGames
     *
     * @return void
     */
    public function testGenerateFirstRoundPlayoffs()
    {
        $this->playoffService->generateFirstRoundPlayoffs();

        // Check that four games have been created
        $games = $this->entityManager->getRepository(Playoffs::class)->findAll();
        $this->assertCount(4, $games);

        // Check that each game has a correct round
        foreach ($games as $game) {
            $this->assertEquals(1, $game->getRound());
        }

        // Check that each game will take place between a team from Division A and Division B
        $topTeamsA = $this->playoffService->findTopTeamsByDivision('A');
        $topTeamsB = $this->playoffService->findTopTeamsByDivision('B');
        foreach ($games as $game) {
            $team1 = $game->getTeam1();
            $team2 = $game->getTeam2();
            $this->assertContains($team1, $topTeamsA);
            $this->assertContains($team2, $topTeamsB);
        }
    }

    /**
     * @depends testGenerateFirstRoundPlayoffs
     * @return void
     */
    public function testGenerateRestGamesOfPlayoffs()
    {
        // Call the method that should generate the games for the remaining teams
        $this->playoffService->generateRestGamesOfPlayoffs();

        // Get records of all playoff games
        $playoffRecords = $this->playoffService->getAllGames();

        // Check that there are two teams in each game
        foreach ($playoffRecords as $playoffRecord) {
            $this->assertInstanceOf(Playoffs::class, $playoffRecord);
            $this->assertInstanceOf(Team::class, $playoffRecord->getTeam1());
            $this->assertInstanceOf(Team::class, $playoffRecord->getTeam2());
        }

        // Check that each game has a winner
        foreach ($playoffRecords as $playoffRecord) {
            $this->assertInstanceOf(Playoffs::class, $playoffRecord);
            $this->assertInstanceOf(Team::class, $playoffRecord->getWinner());
        }

        // Check that the Playoff table has generated 4 records of the first round
        $firstRoundRecords = array_filter($playoffRecords, function ($record) {
            return $record->getRound() === 1;
        });
        $this->assertCount(4, $firstRoundRecords);

        // check that 1 record of the second round was generated in the Playoff table
        $secondRoundRecords = array_filter($playoffRecords, function ($record) {
            return $record->getRound() === 2;
        });
        $this->assertCount(1, $secondRoundRecords);

        // check that 1 record of the third round was generated in the Playoff table
        $thirdRoundRecords = array_filter($playoffRecords, function ($record) {
            return $record->getRound() === 3;
        });
        $this->assertCount(1, $thirdRoundRecords);

        // check that 1 record of the fourth round was generated in the Playoff table
        $fourthRoundRecords = array_filter($playoffRecords, function ($record) {
            return $record->getRound() === 4;
        });

        $this->assertCount(1, $fourthRoundRecords);
    }
}