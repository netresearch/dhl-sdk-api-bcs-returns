<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class SimpleAddress
 *
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class SimpleAddress implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name1;

    /**
     * @var string
     */
    private $streetName;

    /**
     * @var string
     */
    private $houseNumber;

    /**
     * @var string
     */
    private $postCode;

    /**
     * @var string
     */
    private $city;

    /**
     * @var null|Country
     */
    private $country;

    /**
     * @var null|string
     */
    private $name2;

    /**
     * @var null|string
     */
    private $name3;

    /**
     * SimpleAddress constructor.
     * @param string $name1
     * @param string $streetName
     * @param string $houseNumber
     * @param string $postCode
     * @param string $city
     */
    public function __construct(
        string $name1,
        string $streetName,
        string $houseNumber,
        string $postCode,
        string $city
    ) {
        $this->name1 = $name1;
        $this->streetName = $streetName;
        $this->houseNumber = $houseNumber;
        $this->postCode = $postCode;
        $this->city = $city;
    }

    /**
     * @param Country|null $country
     * @return SimpleAddress
     */
    public function setCountry(Country $country = null): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param string|null $name2
     * @return SimpleAddress
     */
    public function setName2(string $name2 = null): self
    {
        $this->name2 = $name2;

        return $this;
    }

    /**
     * @param string|null $name3
     * @return SimpleAddress
     */
    public function setName3(string $name3 = null): self
    {
        $this->name3 = $name3;

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
