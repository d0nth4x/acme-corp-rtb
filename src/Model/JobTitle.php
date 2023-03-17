<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class JobTitle
{
    #[Assert\NotBlank]
    private string $name;

    #[Assert\Type('bool')]
    private bool $enabled;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
