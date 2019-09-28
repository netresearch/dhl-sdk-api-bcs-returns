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
     *
     * @param int $count
     * @param string $positionDescription
     * @param float $value
     * @param int $weightInGrams
     * @param string $articleReference
     */
    public function __construct(
        int $count,
        int $weightInGrams,
        string $positionDescription,
        float $value,
        string $articleReference
    ) {
        $this->count = $count;
        $this->weightInGrams = $weightInGrams;
        $this->positionDescription = $positionDescription;
        $this->values = $value;
        $this->articleReference = $articleReference;
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
