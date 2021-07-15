<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AccountRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[ORM\Table(name: "`account`")]
#[ApiResource(
    collectionOperations: ["get", "post"],
    itemOperations: [
        "get" => ["normalization_context" => ["groups" => ["account:read", "account:item:get"]]],
        "patch"
    ],
    denormalizationContext: ["groups" => ["account:write"]],
    normalizationContext: ["groups" => ["account:read"]]
)]
class Account extends AbstractEntity
{
    #[ORM\ManyToOne(targetEntity: Bank::class)]
    #[ORM\JoinColumn(name: "`bank_id`", referencedColumnName: "id", onDelete: "SET NULL")]
    #[Groups(["account:item:get", "account:write"])]
    private ?Bank $bank;
    
    #[ORM\OneToOne(targetEntity: Contact::class, mappedBy: "account")]
    #[ORM\JoinColumn(name: "`contact_id`", referencedColumnName: "id", onDelete: "CASCADE")]
    #[Groups(["account:item:get", "account:write"])]
    private ?Contact $contact;
    
    #[ORM\Column(name: "`name`", type: "string", length: 255)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["account:read", "account:write"])]
    private string $name;
    
    #[ORM\Column(name: "`account_holder`", type: "string", length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(["account:read", "account:write"])]
    private ?string $accountHolder;
    
    #[ORM\Column(name: "`bank_code`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["account:read", "account:write"])]
    private ?string $bankCode;
    
    #[ORM\Column(name: "`account_number`", type: "string", length: 64, nullable: true)]
    #[Assert\Length(min: 1, max: 64)]
    #[Groups(["account:read", "account:write"])]
    private ?string $accountNumber;
    
    #[ORM\Column(name: "`bic`", type: "string", length: 11, nullable: true)]
    #[Assert\Regex(pattern: "/^([a-zA-Z]{4})([a-zA-Z]{2})(([2-9a-zA-Z]{1})([0-9a-np-zA-NP-Z]{1}))((([0-9a-wy-zA-WY-Z]{1})([0-9a-zA-Z]{2}))|([xX]{3})|)$/")]
    #[Groups(["account:item:get", "account:write"])]
    private ?string $bic;
    
    #[ORM\Column(name: "`iban`", type: "string", length: 34, nullable: true)]
    #[Assert\Regex(pattern: "/^(?:(?:IT|SM)\d{2}[A-Z]\d{22}|CY\d{2}[A-Z]\d{23}|NL\d{2}[A-Z]{4}\d{10}|LV\d{2}[A-Z]{4}\d{13}|(?:BG|BH|GB|IE)\d{2}[A-Z]{4}\d{14}|GI\d{2}[A-Z]{4}\d{15}|RO\d{2}[A-Z]{4}\d{16}|KW\d{2}[A-Z]{4}\d{22}|MT\d{2}[A-Z]{4}\d{23}|NO\d{13}|(?:DK|FI|GL|FO)\d{16}|MK\d{17}|(?:AT|EE|KZ|LU|XK)\d{18}|(?:BA|HR|LI|CH|CR)\d{19}|(?:GE|DE|LT|ME|RS)\d{20}|IL\d{21}|(?:AD|CZ|ES|MD|SA)\d{22}|PT\d{23}|(?:BE|IS)\d{24}|(?:FR|MR|MC)\d{25}|(?:AL|DO|LB|PL)\d{26}|(?:AZ|HU)\d{27}|(?:GR|MU)\d{28})$/")]
    #[Groups(["account:item:get", "account:write"])]
    private ?string $iban;
    
    #[ORM\Column(name: "`opening_balance`", type: "integer", nullable: true)]
    #[Assert\Type("integer")]
    #[Groups(["account:item:get", "account:write"])]
    private ?int $openingBalance;
    
    #[ORM\Column(name: "`is_active`", type: "boolean")]
    #[Assert\NotNull]
    #[Assert\Type("boolean")]
    #[Groups(["account:item:get", "account:write"])]
    private bool $active = true;
    
    #[Groups(["account:read"])]
    public function getId(): ?int
    {
        return parent::getId();
    }
    
    /**
     * @return Bank|null
     */
    public function getBank(): ?Bank
    {
        return $this->bank;
    }
    
    /**
     * @param Bank|null $bank
     * @return Account
     */
    public function setBank(?Bank $bank): Account
    {
        $this->bank = $bank;
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
     * @return Account
     */
    public function setContact(?Contact $contact): Account
    {
        $this->contact = $contact;
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
     * @return Account
     */
    public function setName(string $name): Account
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAccountHolder(): ?string
    {
        return $this->accountHolder;
    }
    
    /**
     * @param string|null $accountHolder
     * @return Account
     */
    public function setAccountHolder(?string $accountHolder): Account
    {
        $this->accountHolder = $accountHolder;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getBankCode(): ?string
    {
        return $this->bankCode;
    }
    
    /**
     * @param string|null $bankCode
     * @return Account
     */
    public function setBankCode(?string $bankCode): Account
    {
        $this->bankCode = $bankCode;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }
    
    /**
     * @param string|null $accountNumber
     * @return Account
     */
    public function setAccountNumber(?string $accountNumber): Account
    {
        $this->accountNumber = $accountNumber;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getBic(): ?string
    {
        return $this->bic;
    }
    
    /**
     * @param string|null $bic
     * @return Account
     */
    public function setBic(?string $bic): Account
    {
        $this->bic = $bic;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }
    
    /**
     * @param string|null $iban
     * @return Account
     */
    public function setIban(?string $iban): Account
    {
        $this->iban = $iban;
        return $this;
    }
    
    /**
     * @return int|null
     */
    public function getOpeningBalance(): ?int
    {
        return $this->openingBalance;
    }
    
    /**
     * @param int|null $openingBalance
     * @return Account
     */
    public function setOpeningBalance(?int $openingBalance): Account
    {
        $this->openingBalance = $openingBalance;
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
     * @return Account
     */
    public function setActive(bool $active): Account
    {
        $this->active = $active;
        return $this;
    }
    
    /**
     * @return string|null
     */
    #[Groups(["account:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
    
    /**
     * @return string|null
     */
    #[Groups(["account:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
