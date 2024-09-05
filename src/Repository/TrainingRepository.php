<?php

// src/Repository/TrainingRepository.php

namespace App\Repository;

use App\Entity\Training;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    /**
     * Find trainings by selected modules.
     *
     * @param array $moduleIds
     * @return Training[]
     */
    public function findByModules(array $moduleIds): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.modules', 'm')
            ->where('m.id IN (:modules)')
            ->setParameter('modules', $moduleIds)
            ->groupBy('t.id')
            ->having('COUNT(m.id) = :moduleCount')
            ->setParameter('moduleCount', count($moduleIds));

        return $qb->getQuery()->getResult();
    }

    public function findByAnyModule(array $selectedModules): array
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.modules', 'm')
            ->where('m.id IN (:selectedModules)')
            ->setParameter('selectedModules', $selectedModules)
            ->groupBy('t.id');

        return $qb->getQuery()->getResult();
    }
}