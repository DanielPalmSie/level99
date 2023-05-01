<?php

namespace App\Services;

use App\Entity\Team;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class TeamService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function generateTeamsTable(): array
    {
        $this->entityManager->createQuery('DELETE FROM App\Entity\Team')->execute();

        $divisionATeams = new ArrayCollection();
        $divisionBTeams = new ArrayCollection();

        // Create 8 teams in Division A
        $divisionA = range('A', 'H');

        foreach ($divisionA as $name) {
            $team = new Team();
            $team->setName($name);
            $team->setDivision('A');
            $this->entityManager->persist($team);
            $divisionATeams->add((object) ['name' => $team->getName(), 'division' => $team->getDivision()]);
        }

        // Create 8 teams in Division B
        $divisionB = range('I', 'P');

        foreach ($divisionB as $name) {
            $team = new Team();
            $team->setName($name);
            $team->setDivision('B');
            $this->entityManager->persist($team);
            $divisionBTeams->add((object) ['name' => $team->getName(), 'division' => $team->getDivision()]);
        }

        $this->entityManager->flush();

        return [
            'DivisionA' => $divisionATeams->toArray(),
            'DivisionB' => $divisionBTeams->toArray(),
        ];
    }
}
