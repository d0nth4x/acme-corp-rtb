<?php

namespace App\Manager;

use App\Entity\Employee;
use App\Model\Employee as EmployeeModel;
use App\Model\Filter\EmployeeFilter;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;

/**
 * @method EmployeeRepository getRepository()
 * @method Employee           save(User $user)
 * @method Employee           find(int $id)
 * @method Employee[]         findAll()
 * @method Employee           findOneBy(array $array)
 * @method Employee[]         findBy(array $array, array $order = null)
 * @method void               remove()
 */
class EmployeeManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
        private readonly JobTitleManager $jobTitleManager
    ) {
        parent::__construct($entityManager, Employee::class);
    }

    public function findEmployee(int $id): Employee
    {
        $employee = $this->find($id);
        if (!$employee) {
            throw new \Exception('Employee not found');
        }

        return $employee;
    }

    public function findSubordinates(Employee $employee, bool $direct): array
    {
        return $this->getRepository()->childrenHierarchy($employee, $direct);
    }

    public function filterBy(EmployeeFilter $filter): array
    {
        return $this->getRepository()->filterBy($filter);
    }

    public function create(EmployeeModel $model): Employee
    {
        return $this->save(
            $this->mapEntity($model)
        );
    }

    public function update(EmployeeModel $model, Employee $entity): Employee
    {
        return $this->save(
            $this->mapEntity($model, $entity)
        );
    }

    public function mapEntity(EmployeeModel $model, Employee $entity = new Employee()): Employee
    {
        $entity->setName($model->getName());
        $entity->setSurname($model->getSurname());

        $entity->setTreeParent(
            $model->getParentId() ? $this->findEmployee($model->getParentId()) : null
        );

        $entity->setJobTitle(
            $this->jobTitleManager->findOneEnabled($model->getJobTitleId())
        );

        return $entity;
    }

    public function remove(Employee $entity): void
    {
        if (!$entity->getChildren()->isEmpty()) {
            throw new \Exception('Employee has children. Move structure to other entity');
        }

        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }
}
