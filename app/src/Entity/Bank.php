<?php
declare(strict_types = 1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BankRepository;
use Carbon\Carbon;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entity that represents the individual banks (Banken).
 * Will be filled with a csv import.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
#[ORM\Entity(repositoryClass: BankRepository::class)]
#[ORM\Table(name: "`bank`")]
#[ApiResource(
    collectionOperations: ["get"],
    itemOperations: ["get" => ["normalization_context" => ["groups" => ["bank:read", "bank:item:get"]]]],
    normalizationContext: ["groups" => ["bank:read"]]
)]
class Bank extends AbstractEntity
{
    #[ORM\Column(name: "`bic`", type: "string", length: 11)]
    #[Assert\NotNull]
    #[Assert\Regex(pattern: "/^([a-zA-Z]{4})([a-zA-Z]{2})(([2-9a-zA-Z]{1})([0-9a-np-zA-NP-Z]{1}))((([0-9a-wy-zA-WY-Z]{1})([0-9a-zA-Z]{2}))|([xX]{3})|)$/")]
    #[Groups(["bank:item:get"])]
    private string $bic;

    #[ORM\Column(name: "`name`", type: "string", length: 58)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 58)]
    #[Groups(["bank:item:get"])]
    private string $name;

    #[ORM\Column(name: "`short_name`", type: "string", length: 27)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 27)]
    #[Groups(["bank:item:get"])]
    private string $shortName;

    #[ORM\Column(name: "`postal_code`", type: "string", length: 5)]
    #[Assert\NotNull]
    #[Assert\Regex(pattern: "/^[0-9]{8}$/")]
    #[Groups(["bank:item:get"])]
    private string $postalCode;

    #[ORM\Column(name: "`locality`", type: "string", length: 35)]
    #[Assert\NotNull]
    #[Assert\Length(min: 1, max: 35)]
    #[Groups(["bank:item:get"])]
    private string $locality;

    #[Groups(["bank:read"])]
    public function getId(): ?int
    {
        return parent::getId();
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

    /**
     * @return string|null
     */
    #[Groups(["bank:item:get"])]
    #[SerializedName("createdAt")]
    public function getCreatedAtAgo(): ?string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }

    /**
     * @return string|null
     */
    #[Groups(["bank:item:get"])]
    #[SerializedName("updatedAt")]
    public function getUpdatedAtAgo(): ?string
    {
        return Carbon::instance($this->getUpdatedAt())->diffForHumans();
    }
}
