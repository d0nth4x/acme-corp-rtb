<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Employee
{
    #[Assert\NotBlank]
    private string $name;

    #[Assert\NotBlank]
    private string $surname;

    #[Assert\NotBlank]
    private int $jobTitleId;

    private ?int $parentId = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getJobTitleId(): int
    {
        return $this->jobTitleId;
    }

    public function setJobTitleId(int $jobTitleId): void
    {
        $this->jobTitleId = $jobTitleId;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }
}
