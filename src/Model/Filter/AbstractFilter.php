<?php

namespace App\Model\Filter;

abstract class AbstractFilter
{
    private ?int $limitFrom;

    private ?int $limitTo;

    public function getLimitFrom(): ?int
    {
        return $this->limitFrom;
    }

    public function setLimitFrom(?int $limitFrom): void
    {
        $this->limitFrom = $limitFrom;
    }

    public function getLimitTo(): ?int
    {
        return $this->limitTo;
    }

    public function setLimitTo(?int $limitTo): void
    {
        $this->limitTo = $limitTo;
    }
}
