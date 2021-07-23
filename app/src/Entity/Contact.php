<?php
declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity that represents the individual contacts (Amazon, Aldi).
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: "`contact`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => ["contact:read", "contact:item:get"]]],
        "patch"
    ],
    denormalizationContext: ["groups" => ["contact:write"]],
    normalizationContext: ["groups" => ["contact:read"]]
)]
class Contact extends AbstractEntity
{
    #[ORM\OneToOne(inversedBy: "contact", targetEntity: Account::class)]
    #[ORM\JoinColumn(name: "`account_id`", referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(["contact:item:get", "contact:write"])]
    private ?Account $account;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["contact:item:get", "contact:write"])]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000, nullable: true)]
    #[Assert\Length(min: 1, max: 1000)]
    #[Groups(["contact:item:get", "contact:write"])]
    private ?string $description;
    
    #[ORM\Column(name: "`is_active`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["contact:item:get", "contact:write"])]
    private bool $active = true;

    #[Groups(["contact:read"])]
    public function getId(): ?int
    {
        return parent::getId();
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
     * @return Contact
     */
    public function setAccount(?Account $account): Contact
    {
        $this->account = $account;
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
     * @return Contact
     */
    public function setName(string $name): Contact
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
     * @return Contact
     */
    public function setDescription(?string $description): Contact
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
     * @return Contact
     */
    public function setActive(bool $active): Contact
    {
        $this->active = $active;
        return $this;
    }

    /**
     * @return string|null
     */
    #[Groups(["contact:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["contact:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
