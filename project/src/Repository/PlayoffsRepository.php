<?php

namespace App\Repository;

use App\Entity\Playoffs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Playoffs>
 *
 * @method Playoffs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playoffs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playoffs[]    findAll()
 * @method Playoffs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayoffsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playoffs::class);
    }

    public function save(Playoffs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playoffs $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPlayoffRecords(): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->addSelect('p.round')
            ->addSelect('t1.name AS team1_name')
            ->addSelect('t1.division AS team1_division')
            ->addSelect('t2.name AS team2_name')
            ->addSelect('t2.division AS team2_division')
            ->addSelect('w.name AS winner_name')
            ->addSelect('w.division AS winner_division')
            ->leftJoin('p.team1', 't1')
            ->leftJoin('p.team2', 't2')
            ->leftJoin('p.winner', 'w')
            ->getQuery();

        $records = $qb->getArrayResult();

        return array_map(function ($record) {
            return [
                'id' => $record['id'],
                'round' => $record['round'],
                'team_1' => [
                    'name' => $record['team1_name'],
                    'division' => $record['team1_division'],
                ],
                'team_2' => [
                    'name' => $record['team2_name'],
                    'division' => $record['team2_division'],
                ],
                'winner' => [
                    'name' => $record['winner_name'],
                    'division' => $record['winner_division'],
                ],
            ];
        }, $records);
    }
}
