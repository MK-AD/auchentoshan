<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EntryRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity that represents the entries.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
#[ORM\Entity(repositoryClass: EntryRepository::class)]
#[ORM\Table(name: "`entry`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: [
        "get"  => ["normalization_context" => ["groups" => ["entry:read", "entry:item:get"]]],
        "patch",
        "delete"
    ]
)]
class Entry extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Entry::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "`parent_id`", referencedColumnName: "id")]
    #[Groups(["entry:item:get", "entry:write"])]
    private ?Entry $parent;
    
    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: "`account_id`", referencedColumnName: "id")]
    #[Groups(["entry:item:get", "entry:write"])]
    private ?Account $account;
    
    #[ORM\ManyToOne(targetEntity: Contact::class)]
    #[ORM\JoinColumn(name: "`contact_id`", referencedColumnName: "id")]
    #[Groups(["entry:item:get", "entry:write"])]
    private ?Contact $contact;
    
    #[ORM\ManyToOne(targetEntity: Savings::class, inversedBy: "entries")]
    #[ORM\JoinColumn(name: "`savings_id", referencedColumnName: "id")]
    #[Groups(["entry:item:get", "entry:write"])]
    private ?Savings $savings;
    
    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(name: "`category_id`", referencedColumnName: "id")]
    #[Groups(["entry:item:get", "entry:write"])]
    private Category $category;
    
    #[ORM\Column(name: "`date`", type: "date")]
    #[Assert\NotNull]
    #[Assert\Type("DateTime")]
    #[Groups(["entry:item:get", "entry:write"])]
    private DateTime $date;
    
    #[ORM\Column(name: "`amount`", type: "integer")]
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[Groups(["entry:item:get", "entry:write"])]
    private int $amount;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000, nullable: true)]
    #[Assert\Length(min: 1, max: 1000)]
    #[Groups(["entry:item:get", "entry:write"])]
    private ?string $description;

    #[Groups(["entry:read"])]
    public function getId(): ?int
    {
        return parent::getId();
    }

    /**
     * @return Entry|null
     */
    public function getParent(): ?Entry
    {
        return $this->parent;
    }
    
    /**
     * @param Entry|null $parent
     * @return Entry
     */
    public function setParent(?Entry $parent): Entry
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }
    
    /**
     * @param Account|null $account
     * @return Entry
     */
    public function setAccount(?Account $account): Entry
    {
        $this->account = $account;
        return $this;
    }
    
    /**
     * @return Contact|null
     */
    public function getContact(): ?Contact
    {
        return $this->contact;
    }
    
    /**
     * @param Contact|null $contact
     * @return Entry
     */
    public function setContact(?Contact $contact): Entry
    {
        $this->contact = $contact;
        return $this;
    }
    
    /**
     * @return Savings|null
     */
    public function getSavings(): ?Savings
    {
        return $this->savings;
    }
    
    /**
     * @param Savings|null $savings
     * @return Entry
     */
    public function setSavings(?Savings $savings): Entry
    {
        $this->savings = $savings;
        return $this;
    }
    
    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
    
    /**
     * @param Category $category
     * @return Entry
     */
    public function setCategory(Category $category): Entry
    {
        $this->category = $category;
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }
    
    /**
     * @param DateTime $date
     * @return Entry
     */
    public function setDate(DateTime $date): Entry
    {
        $this->date = $date;
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
     * @return Entry
     */
    public function setAmount(int $amount): Entry
    {
        $this->amount = $amount;
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
     * @return Entry
     */
    public function setDescription(?string $description): Entry
    {
        $this->description = $description;
        return $this;
    }
    
    #[ORM\OneToMany(targetEntity: Entry::class, mappedBy: "parent")]
    private iterable $children;
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    /**
     * @return ArrayCollection|iterable
     */
    public function getChildren(): iterable|ArrayCollection
    {
        return $this->children;
    }
    
    /**
     * @param ArrayCollection|iterable $children
     * @return Entry
     */
    public function setChildren(iterable|ArrayCollection $children): Entry
    {
        $this->children = $children;
        return $this;
    }
    
    /**
     * @param Entry $child
     * @return Entry
     */
    public function addChild(Entry $child): Entry
    {
        if (!$this->children->contains($child)) {
            $child->setParent($this);
            $this->children->add($child);
        }
        
        return $this;
    }
    
    /**
     * @param Entry $child
     * @return Entry
     */
    public function removeChild(Entry $child): Entry
    {
        if ($this->children->contains($child)) {
            $child->setParent(null);
            $this->children->removeElement($child);
        }
        
        return $this;
    }

    /**
     * @return string|null
     */
    #[Groups(["entry:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["entry:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
