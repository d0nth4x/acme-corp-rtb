<?php

namespace App\Repository;

use App\Entity\JobTitle;
use App\Model\Filter\JobTitleFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobTitleRepository>
 *
 * @method null|JobTitle find($id, $lockMode = null, $lockVersion = null)
 * @method null|JobTitle findOneBy(array $criteria, array $orderBy = null)
 * @method JobTitle[]    findAll()
 * @method JobTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobTitle::class);
    }

    public function filterBy(JobTitleFilter $filter): array
    {
        $stmt = $this->createQueryBuilder('c')
            ->setFirstResult($filter->getLimitFrom())
            ->setMaxResults($filter->getLimitTo())
        ;

        if (null !== $filter->getEnabled()) {
            $stmt->where('c.enabled = :enabled')
                ->setParameter('enabled', $filter->getEnabled())
            ;
        }

        return $stmt->getQuery()->getResult();
    }
}
