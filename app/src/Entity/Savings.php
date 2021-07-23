<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Enum\InstallmentFrequency;
use App\Repository\SavingsRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity that represents savings plans.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
#[ORM\Entity(repositoryClass: SavingsRepository::class)]
#[ORM\Table(name: "`savings`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => ["savings:read", "savings:item:get"]]],
        "patch",
        "delete"
    ]
)]
class Savings extends AbstractEntity
{
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["savings:item:get", "savings:write"])]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000, nullable: true)]
    #[Assert\Length(min: 1, max: 1000)]
    #[Groups(["savings:item:get", "savings:write"])]
    private ?string $description;
    
    #[ORM\Column(name: "`amount`", type: "integer")]
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0)]
    #[Groups(["savings:item:get", "savings:write"])]
    private int $amount;
    
    #[ORM\Column(name: "`installment`", type: "integer")]
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0)]
    #[Groups(["savings:item:get", "savings:write"])]
    private int $installment;
    
    /**
     * Installment frequency. Possible values are 'daily', 'weekly', 'biweekly', 'monthly', 'quarterly', and 'annual'.
     */
    #[ORM\Column(name: "`installment_frequency`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Choice(callback: [InstallmentFrequency::class, "getEnumMap"])]
    #[Groups(["savings:item:get", "savings:write"])]
    private string $installmentFrequency;
    
    /**
     * The start date for the savings plan.
     */
    #[ORM\Column(name: "`start_date`", type: "date", nullable: true)]
    #[Assert\Type("DateTime")]
    #[Groups(["savings:item:get", "savings:write"])]
    private ?DateTime $startDate;
    
    /**
     * The end date for the savings plan. Please note that this has to make sense in combination with the installment.
     * A timespan of 2 months with a monthly installment of 50 EUR will definitely not yield 5000 EUR.
     */
    #[ORM\Column(name: "`end_date`", type: "date", nullable: true)]
    #[Assert\Type("DateTime")]
    #[Groups(["savings:item:get", "savings:write"])]
    private ?DateTime $endDate;
    
    #[ORM\OneToMany(mappedBy: "savings", targetEntity: Entry::class)]
    #[Groups(["savings:item:get", "savings:write"])]
    private iterable $entries;

    #[Groups(["savings:read"])]
    public function getId(): ?int
    {
        return parent::getId();
    }

    public function __construct()
    {
        $this->entries = new ArrayCollection();
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
     * @return Savings
     */
    public function setName(string $name): Savings
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
     * @return Savings
     */
    public function setDescription(?string $description): Savings
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
     * @return Savings
     */
    public function setAmount(int $amount): Savings
    {
        $this->amount = $amount;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getInstallment(): int
    {
        return $this->installment;
    }
    
    /**
     * @param int $installment
     * @return Savings
     */
    public function setInstallment(int $installment): Savings
    {
        $this->installment = $installment;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getInstallmentFrequency(): string
    {
        return $this->installmentFrequency;
    }
    
    /**
     * @param string $installmentFrequency
     * @return Savings
     */
    public function setInstallmentFrequency(string $installmentFrequency): Savings
    {
        $this->installmentFrequency = $installmentFrequency;
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
     * @return Savings
     */
    public function setStartDate(?DateTime $startDate): Savings
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
     * @return Savings
     */
    public function setEndDate(?DateTime $endDate): Savings
    {
        $this->endDate = $endDate;
        return $this;
    }
    
    /**
     * @return ArrayCollection|iterable
     */
    public function getEntries(): iterable|ArrayCollection
    {
        return $this->entries;
    }
    
    /**
     * @param Entry $entry
     * @return Savings
     */
    public function addEntry(Entry $entry): Savings
    {
        if (!$this->entries->contains($entry)) {
            $entry->setSavings($this);
            $this->entries->add($entry);
        }
        
        return $this;
    }
    
    /**
     * @param Entry $entry
     * @return Savings
     */
    public function removeEntry(Entry $entry): Savings
    {
        if ($this->entries->contains($entry)) {
            $entry->setSavings(null);
            $this->entries->removeElement($entry);
        }
        
        return $this;
    }

    /**
     * @return string|null
     */
    #[Groups(["savings:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["savings:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
