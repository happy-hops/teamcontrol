<?php

namespace App\Repository;

use App\Entity\Driver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DriverRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Driver::class);
    }

    /** @return Driver[] */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return Driver[] */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.name LIKE :q')
            ->setParameter('q', '%' . $query . '%')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
