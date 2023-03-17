<?php

namespace App\Model\Filter;

class EmployeeFilter extends AbstractFilter
{
    private ?int $jobTitleId;

    private ?int $onlyRoot = 0;

    public function getJobTitleId(): ?int
    {
        return $this->jobTitleId;
    }

    public function setJobTitleId(?int $jobTitleId): void
    {
        $this->jobTitleId = $jobTitleId;
    }

    public function getOnlyRoot(): ?int
    {
        return $this->onlyRoot;
    }

    public function setOnlyRoot(?int $onlyRoot): void
    {
        $this->onlyRoot = $onlyRoot;
    }
}
