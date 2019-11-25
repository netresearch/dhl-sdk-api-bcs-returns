<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class Country
 *
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
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

    /**
     * Country constructor.
     * @param string $countryISOCode
     */
    public function __construct(string $countryISOCode)
    {
        $this->countryISOCode = $countryISOCode;
    }

    /**
     * @param string|null $country
     * @return Country
     */
    public function setCountry(string $country = null): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param string|null $state
     * @return Country
     */
    public function setState(string $state = null): self
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
