<?php

namespace App\Repository;

use App\Entity\Race;
use App\Enum\RaceState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Race::class);
    }

    public function findById(int $id): ?Race
    {
        return $this->find($id);
    }

    public function findBySlug(string $slug): ?Race
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * Gibt das aktive Rennen zurück.
     * Falls keins aktiv ist, das nächste geplante.
     */
    public function findCurrent(): ?Race
    {
        return $this->createQueryBuilder('r')
            ->where('r.state = :active')
            ->setParameter('active', RaceState::Active)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ?? $this->createQueryBuilder('r')
                ->where('r.state = :planned')
                ->setParameter('planned', RaceState::Planned)
                ->orderBy('r.scheduled', 'ASC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
    }

    /** @return Race[] */
    public function findAll(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.scheduled', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(Race $race): void
    {
        $this->getEntityManager()->persist($race);
        $this->getEntityManager()->flush();
    }

    public function remove(Race $race): void
    {
        $this->getEntityManager()->remove($race);
        $this->getEntityManager()->flush();
    }
}
