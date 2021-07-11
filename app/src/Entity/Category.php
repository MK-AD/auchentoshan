<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ORM\Table(name: "`category`")]
#[ApiResource]
class Category extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "children")]
    #[ORM\JoinColumn(name: "`parent_id`", referencedColumnName: "id")]
    private ?Category $parent;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000)]
    #[Assert\Length(min: 1, max: 1000)]
    private ?string $description;
    
    #[ORM\OneToMany(targetEntity: Category::class, mappedBy: "parent")]
    private iterable $children;
    
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
}
