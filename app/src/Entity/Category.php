<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Carbon\Carbon;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity that represents the individual categories (Clothes, Food, Electronics).
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: "`category`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: [
        "get"  => ["normalization_context" => ["groups" => ["category:read", "category:item:get"]]],
        "patch"],
    denormalizationContext: ["groups" => ["category:write"]],
    normalizationContext: ["groups" => ["category:read"]]
)]
class Category extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "`parent_id`", referencedColumnName: "id")]
    #[Groups(["category:item:get", "category:write"])]
    private ?Category $parent;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["category:item:get", "category:write"])]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000)]
    #[Assert\Length(min: 1, max: 1000)]
    #[Groups(["category:item:get", "category:write"])]
    private ?string $description;
    
    #[ORM\Column(name: "`is_active`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["category:item:get", "category:write"])]
    private bool $active = true;
    
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: "parent")]
    private iterable $children;

    #[Groups(["category:read"])]
    public function getId(): ?int
    {
        return parent::getId();
    }
    
    public function __construct()
    {
        $this->children = new ArrayCollection();
    }
    
    /**
     * @return Category|null
     */
    public function getParent(): ?Category
    {
        return $this->parent;
    }
    
    /**
     * @param Category|null $parent
     * @return Category
     */
    public function setParent(?Category $parent): Category
    {
        $this->parent = $parent;
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
     * @return Category
     */
    public function setName(string $name): Category
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
     * @return Category
     */
    public function setDescription(?string $description): Category
    {
        $this->description = $description;
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
     * @return Category
     */
    public function setActive(bool $active): Category
    {
        $this->active = $active;
        return $this;
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
     * @return Category
     */
    public function setChildren(iterable|ArrayCollection $children): Category
    {
        $this->children = $children;
        return $this;
    }
    
    /**
     * @param Category $child
     * @return Category
     */
    public function addChild(Category $child): Category
    {
        if (!$this->children->contains($child)) {
            $child->setParent($this);
            $this->children->add($child);
        }
        
        return $this;
    }
    
    /**
     * @param Category $child
     * @return Category
     */
    public function removeChild(Category $child): Category
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
    #[Groups(["category:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["category:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
