<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * Find approved jobs with optional search query
     */
    public function findApprovedJobs(?string $searchQuery = null): array
    {
        $qb = $this->createQueryBuilder('j')
            ->where('j.status = :approved')
            ->setParameter('approved', 'approved')
            ->orderBy('j.createdAt', 'DESC');

        if ($searchQuery) {
            $qb->andWhere('j.title LIKE :search OR j.company LIKE :search OR j.location LIKE :search OR j.field LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find pending jobs for admin approval
     */
    public function findPendingJobs(): array
    {
        return $this->createQueryBuilder('j')
            ->where('j.status = :pending')
            ->setParameter('pending', 'pending')
            ->orderBy('j.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
