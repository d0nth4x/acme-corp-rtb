<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Repository\EmployeeRepository;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;

#[Gedmo\Tree(type: 'nested')]
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ORM\Table]
#[JMS\AccessorOrder(order: 'custom', custom: ['id', 'name', 'surname', 'hasChildren'])]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Groups(['employees_list'])]
    private int $id;

    #[ORM\Column]
    #[JMS\Groups(['employees_list'])]
    private string $name;

    #[ORM\Column]
    #[JMS\Groups(['employees_list'])]
    private string $surname;

    #[ORM\ManyToOne(targetEntity: JobTitle::class)]
    private JobTitle $jobTitle;

    #[Gedmo\TreeLeft]
    #[ORM\Column]
    private int $treeLeft;

    #[Gedmo\TreeLevel]
    #[ORM\Column]
    private int $treeLevel;

    #[Gedmo\TreeRight]
    #[ORM\Column]
    private int $treeRight;

    #[Gedmo\TreeRoot]
    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'tree_root', onDelete: 'CASCADE')]
    private Employee $treeRoot;

    #[Gedmo\TreeParent]
    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children')]
    #[ORM\JoinColumn(name: 'tree_parent_id', onDelete: 'CASCADE')]
    private ?Employee $treeParent;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'treeParent')]
    #[ORM\OrderBy(['treeLeft' => 'ASC'])]
    private Collection $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getTreeLeft(): int
    {
        return $this->treeLeft;
    }

    public function setTreeLeft(int $treeLeft): self
    {
        $this->treeLeft = $treeLeft;

        return $this;
    }

    public function getTreeLevel(): int
    {
        return $this->treeLevel;
    }

    public function setTreeLevel(int $treeLevel): self
    {
        $this->treeLevel = $treeLevel;

        return $this;
    }

    public function getTreeRight(): int
    {
        return $this->treeRight;
    }

    public function setTreeRight(int $treeRight): self
    {
        $this->treeRight = $treeRight;

        return $this;
    }

    public function getTreeRoot(): Employee
    {
        return $this->treeRoot;
    }

    public function setTreeRoot(Employee $treeRoot): self
    {
        $this->treeRoot = $treeRoot;

        return $this;
    }

    public function getTreeParent(): Employee
    {
        return $this->treeParent;
    }

    public function setTreeParent(Employee $treeParent): self
    {
        $this->treeParent = $treeParent;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function addChild(Employee $child): self
    {
        if (!$this->children->contains($child)) {
            $child->setTreeParent($this);
            $this->children->add($child);
        }

        return $this;
    }

    #[OA\Property(type: 'boolean')]
    #[JMS\VirtualProperty]
    #[JMS\Groups(['employees_list'])]
    public function hasChildren(): bool
    {
        return !$this->getChildren()->isEmpty();
    }

    public function getJobTitle(): JobTitle
    {
        return $this->jobTitle;
    }

    public function setJobTitle(JobTitle $jobTitle): self
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    #[OA\Property(type: 'integer')]
    #[JMS\VirtualProperty]
    #[JMS\Groups(['employees_list'])]
    public function getJobTitleId(): int
    {
        return $this->jobTitle->getId();
    }
}
