<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BudgetRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BudgetRepository::class)]
#[ORM\Table(name: "`budget`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: ["get", "patch"]
)]
class Budget extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "`category_id`", referencedColumnName: "id")]
    private Category $category;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000, nullable: true)]
    #[Assert\Length(min: 1, max: 1000)]
    private ?string $description;
    
    #[ORM\Column(name: "`amount`", type: "integer")]
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0)]
    private int $amount;
    
    #[ORM\Column(name: "`start_date`", type: "date", nullable: true)]
    #[Assert\Type("DateTime")]
    private ?DateTime $startDate;
    
    #[ORM\Column(name: "`end_date`", type: "date", nullable: true)]
    #[Assert\Type("DateTime")]
    private ?DateTime $endDate;
    
    #[ORM\Column(name: "`is_active`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    private bool $active = true;
    
    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
    
    /**
     * @param Category $category
     * @return Budget
     */
    public function setCategory(Category $category): Budget
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * @return Budget
     */
    public function setName(string $name): Budget
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }
    
    /**
     * @param string|null $description
     * @return Budget
     */
    public function setDescription(?string $description): Budget
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
    
    /**
     * @param int $amount
     * @return Budget
     */
    public function setAmount(int $amount): Budget
    {
        $this->amount = $amount;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }
    
    /**
     * @param DateTime|null $startDate
     * @return Budget
     */
    public function setStartDate(?DateTime $startDate): Budget
    {
        $this->startDate = $startDate;
        return $this;
    }
    
    /**
     * @return DateTime|null
     */
    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }
    
    /**
     * @param DateTime|null $endDate
     * @return Budget
     */
    public function setEndDate(?DateTime $endDate): Budget
    {
        $this->endDate = $endDate;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }
    
    /**
     * @param bool $active
     * @return Budget
     */
    public function setActive(bool $active): Budget
    {
        $this->active = $active;
        return $this;
    }
}
