<?php

namespace App\Model\Filter;

class JobTitleFilter extends AbstractFilter
{
    private ?int $enabled;

    public function getEnabled(): ?int
    {
        return $this->enabled;
    }

    public function setEnabled(?int $enabled): void
    {
        $this->enabled = $enabled;
    }
}
