<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

class Country implements \JsonSerializable
{
    /**
     * @var string
     */
    private $countryISOCode;

    /**
     * @var string|null
     */
    private $country;

    /**
     * @var string|null
     */
    private $state;

    public function __construct(string $countryISOCode)
    {
        $this->countryISOCode = $countryISOCode;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

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
