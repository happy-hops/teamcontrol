<?php

namespace App\Repository;

use App\Entity\Race;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    public function findById(int $id): ?Team
    {
        return $this->find($id);
    }

    public function findByToken(string $token): ?Team
    {
        return $this->findOneBy(['teamToken' => strtoupper(trim($token))]);
    }

    public function findByTokenAndRace(string $token, Race $race): ?Team
    {
        return $this->findOneBy([
            'teamToken' => strtoupper(trim($token)),
            'race'      => $race,
        ]);
    }

    public function save(Team $team): void
    {
        $this->getEntityManager()->persist($team);
        $this->getEntityManager()->flush();
    }

    public function remove(Team $team): void
    {
        $this->getEntityManager()->remove($team);
        $this->getEntityManager()->flush();
    }
}
