<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: "`contact`")]
#[ApiResource]
class Contact extends AbstractEntity
{
    #[ORM\OneToOne(targetEntity: Account::class, inversedBy: "contact")]
    #[ORM\JoinColumn(name: "`account_id`", referencedColumnName: "id", onDelete: "CASCADE")]
    private ?Account $account;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    private string $name;
    
    #[ORM\Column(name: "`description`", type: "string", length: 1000, nullable: true)]
    #[Assert\Length(min: 1, max: 1000)]
    private ?string $description;
    
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
}
