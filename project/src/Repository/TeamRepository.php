<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Team>
 *
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function save(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Team $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTeamsByIds(array $ids)
    {
        return $this->getEntityManager()
            ->getRepository(Team::class)
            ->createQueryBuilder('t')
            ->where('t.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('t.score', 'DESC') // сортировка по score от наивысшего к наименьшему
            ->getQuery()
            ->getResult();
    }

    public function findTopTeamsByDivision(string $division): array
    {
        return $this->getEntityManager()
            ->getRepository(Team::class)
            ->createQueryBuilder('t')
            ->where('t.division = :division')
            ->setParameter('division', $division)
            ->orderBy('t.score', 'DESC')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    public function findTeamsWhichWonInPlayoff()
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT t
    FROM App\Entity\Team t
    JOIN App\Entity\Playoffs p
    WHERE t.id = p.winner'
        );

        return $query->getResult();
    }
}
