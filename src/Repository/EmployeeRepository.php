<?php

namespace App\Repository;

use App\Model\Filter\EmployeeFilter;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class EmployeeRepository extends NestedTreeRepository
{
    public function filterBy(EmployeeFilter $employeeFilter): array
    {
        $stmt = $this->createQueryBuilder('e')
            ->setFirstResult($employeeFilter->getLimitFrom())
            ->setMaxResults($employeeFilter->getLimitTo())
        ;

        if ($employeeFilter->getJobTitleId()) {
            $stmt->join('e.jobTitle', 'jt')
                ->where('jt.id = :jobTitleId')
                ->setParameter('jobTitleId', $employeeFilter->getJobTitleId())
            ;
        }

        if ($employeeFilter->getOnlyRoot()) {
            $stmt->andWhere('e.treeParent IS NULL');
        }

        return $stmt->getQuery()->getResult();
    }
}
