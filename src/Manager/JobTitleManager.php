<?php

namespace App\Manager;

use App\Entity\JobTitle;
use App\Model\Filter\JobTitleFilter;
use App\Model\JobTitle as JobTitleModel;
use App\Repository\JobTitleRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;

/**
 * @method JobTitle           createObject()
 * @method JobTitleRepository getRepository()
 * @method JobTitle           find(int $id)
 * @method JobTitle[]         findAll()
 * @method JobTitle           findOneBy(array $array)
 * @method JobTitle[]         findBy(array $array, array $order = null)
 * @method JobTitle           deserialize(string $data, string $type, string $format = 'json', ?SerializationContext $context = null)
 */
class JobTitleManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
    ) {
        parent::__construct($entityManager, JobTitle::class, $serializer);
    }

    public function findJobTitle(int $id): JobTitle
    {
        $result = $this->getRepository()->find($id);
        if (!$result) {
            throw new \Exception('JobTitle not found');
        }

        return $result;
    }

    public function findOneEnabled(int $id): JobTitle
    {
        $result = $this->getRepository()->findOneBy(['id' => $id, 'enabled' => true]);

        if (!$result) {
            throw new \Exception('JobTitle not found');
        }

        return $result;
    }

    public function filterBy(JobTitleFilter $filter): array
    {
        return $this->getRepository()->filterBy($filter);
    }

    public function create(JobTitleModel $model): JobTitle
    {
        return $this->save(
            $this->mapEntity($model)
        );
    }

    public function update(JobTitleModel $model, JobTitle $entity): JobTitle
    {
        return $this->save(
            $this->mapEntity($model, $entity)
        );
    }

    public function remove(JobTitle $jobTitle): void
    {
        $this->entityManager->remove($jobTitle);
        $this->entityManager->flush();
    }

    public function mapEntity(JobTitleModel $model, JobTitle $entity = new JobTitle()): JobTitle
    {
        $entity->setName($model->getName());
        $entity->setEnabled($model->getEnabled());

        return $entity;
    }
}
