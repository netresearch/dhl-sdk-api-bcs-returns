<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class SimpleAddress
 *
 * @author Andreas Müller <andreas.mueller@netresearch.de>
 * @link   https://www.netresearch.de/
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

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function setName2(?string $name2): self
    {
        $this->name2 = $name2;

        return $this;
    }

    public function setName3(?string $name3): self
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
