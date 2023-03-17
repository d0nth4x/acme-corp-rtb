<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;

class AbstractManager
{
    public function __construct(protected EntityManagerInterface $entityManager, protected string $entityClass)
    {
    }

    public function getRepository(): mixed
    {
        return $this->entityManager->getRepository($this->entityClass);
    }

    public function save(mixed $entity): mixed
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $entity;
    }

    public function find(int|string $id): mixed
    {
        return $this->getRepository()->find($id);
    }

    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    public function findOneBy(array $array): mixed
    {
        return $this->getRepository()->findOneBy($array);
    }

    public function findBy(array $array, array $order = null): mixed
    {
        return $this->getRepository()->findBy($array, $order);
    }
}
