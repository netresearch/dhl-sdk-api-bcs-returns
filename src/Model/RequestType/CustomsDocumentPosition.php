<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class CustomsDocumentPosition
 *
 * @package Dhl\Sdk\Paket\Retoure\Model
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class CustomsDocumentPosition implements \JsonSerializable
{
    /**
     * @var int $count Amount of items declared per position.
     */
    private $count;

    /**
     * @var int $weightInGrams Weight of the returned item.
     */
    private $weightInGrams;

    /**
     * @var float $values Value of returned item.
     */
    private $values;

    /**
     * @var string $positionDescription Description of the returned item.
     */
    private $positionDescription;

    /**
     * @var string $articleReference Reference of the returned item.
     */
    private $articleReference;

    /**
     * @var string|null Country the returned item was produced.
     */
    private $originCountry;

    /**
     * @var string|null $tarifNumber Customs tariff number.
     */
    private $tarifNumber;

    /**
     * CustomsDocumentPosition constructor.
     * @param int $qty
     * @param string $description
     * @param float $value
     * @param int $weightInGrams
     * @param string $sku
     */
    public function __construct(
        int $qty,
        string $description,
        float $value,
        int $weightInGrams,
        string $sku
    ) {
        $this->count = $qty;
        $this->weightInGrams = $weightInGrams;
        $this->values = $value;
        $this->positionDescription = $description;
        $this->articleReference = $sku;
    }

    /**
     * @param string|null $originCountry
     * @return CustomsDocumentPosition
     */
    public function setOriginCountry(string $originCountry = null): self
    {
        $this->originCountry = $originCountry;

        return $this;
    }

    /**
     * @param string|null $tariffNumber
     * @return CustomsDocumentPosition
     */
    public function setTariffNumber(string $tariffNumber = null): self
    {
        $this->tarifNumber = $tariffNumber;

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return mixed[] Serializable object properties
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
