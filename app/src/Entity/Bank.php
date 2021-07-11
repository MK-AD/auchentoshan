<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BankRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: "`bank`")]
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get"]
)]
class Bank extends AbstractEntity
{
    #[ORM\Column(name: "`record_number`", type: "integer")]
    #[Assert\NotNull]
    #[Assert\Type("integer")]
    #[Assert\GreaterThan(0)]
    private int $recordNumber;
    
    #[ORM\Column(name: "`bic`", type: "string", length: 11)]
    #[Assert\NotNull]
    #[Assert\Regex(pattern: "/^([a-zA-Z]{4})([a-zA-Z]{2})(([2-9a-zA-Z]{1})([0-9a-np-zA-NP-Z]{1}))((([0-9a-wy-zA-WY-Z]{1})([0-9a-zA-Z]{2}))|([xX]{3})|)$/")]
    private string $bic;
    
    #[ORM\Column(name: "`name`", type: "string", length: 58)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 58)]
    private string $name;
    
    #[ORM\Column(name: "`short_name`", type: "string", length: 27)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 27)]
    private string $shortName;
    
    #[ORM\Column(name: "`postal_code`", type: "string", length: 5)]
    #[Assert\NotNull]
    #[Assert\Regex(pattern: "/^[0-9]{8}$/")]
    private string $postalCode;
    
    #[ORM\Column(name: "`locality`", type: "string", length: 35)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 35)]
    private string $locality;
    
    /**
     * @return int
     */
    public function getRecordNumber(): int
    {
        return $this->recordNumber;
    }
    
    /**
     * @param int $recordNumber
     * @return Bank
     */
    public function setRecordNumber(int $recordNumber): Bank
    {
        $this->recordNumber = $recordNumber;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getBic(): string
    {
        return $this->bic;
    }
    
    /**
     * @param string $bic
     * @return Bank
     */
    public function setBic(string $bic): Bank
    {
        $this->bic = $bic;
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
     * @return Bank
     */
    public function setName(string $name): Bank
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getShortName(): string
    {
        return $this->shortName;
    }
    
    /**
     * @param string $shortName
     * @return Bank
     */
    public function setShortName(string $shortName): Bank
    {
        $this->shortName = $shortName;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }
    
    /**
     * @param string $postalCode
     * @return Bank
     */
    public function setPostalCode(string $postalCode): Bank
    {
        $this->postalCode = $postalCode;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getLocality(): string
    {
        return $this->locality;
    }
    
    /**
     * @param string $locality
     * @return Bank
     */
    public function setLocality(string $locality): Bank
    {
        $this->locality = $locality;
        return $this;
    }
}
