<?php

namespace App\Repository;

use App\Entity\RaceEvent;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RaceEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceEvent::class);
    }

    /** @return RaceEvent[] */
    public function findByTeam(Team $team): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.team = :team')
            ->setParameter('team', $team)
            ->orderBy('e.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function save(RaceEvent $event): void
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }
}
