<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class ReturnOrder
 *
 * @package Dhl\Sdk\Paket\Retoure\Model
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class ReturnOrder implements \JsonSerializable
{
    const DOCUMENT_TYPE_PDF = 'SHIPMENT_LABEL';
    const DOCUMENT_TYPE_QR = 'QR_LABEL';
    const DOCUMENT_TYPE_BOTH = 'BOTH';

    /**
     * @var string
     */
    private $receiverId;

    /**
     * @var SimpleAddress
     */
    private $senderAddress;

    /**
     * @var string|null
     */
    private $billingNumber;

    /**
     * @var string|null
     */
    private $shipmentReference;

    /**
     * @var string|null $returnDocumentType The type of document(s) to return [SHIPMENT_LABEL, QR_LABEL, BOTH]
     */
    private $returnDocumentType;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $phoneNumber;

    /**
     * @var float|null
     */
    private $value;

    /**
     * @var int|null
     */
    private $weightInGrams;

    /**
     * @var CustomsDocument|null
     */
    private $customsDocument;

    /**
     * ReturnOrder constructor.
     * @param string $receiverId
     * @param SimpleAddress $senderAddress
     */
    public function __construct(
        string $receiverId,
        SimpleAddress $senderAddress
    ) {
        $this->receiverId = $receiverId;
        $this->senderAddress = $senderAddress;
    }

    /**
     * @param string|null $billingNumber
     * @return ReturnOrder
     */
    public function setBillingNumber(string $billingNumber = null): self
    {
        $this->billingNumber = $billingNumber;

        return $this;
    }

    /**
     * @param string|null $shipmentReference
     * @return ReturnOrder
     */
    public function setShipmentReference(string $shipmentReference = null): self
    {
        $this->shipmentReference = $shipmentReference;

        return $this;
    }

    /**
     * @param string|null $returnDocumentType
     * @return ReturnOrder
     */
    public function setReturnDocumentType(string $returnDocumentType = null): self
    {
        $this->returnDocumentType = $returnDocumentType;

        return $this;
    }

    /**
     * @param string|null $email
     * @return ReturnOrder
     */
    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string|null $phoneNumber
     * @return ReturnOrder
     */
    public function setPhoneNumber(string $phoneNumber = null): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * @param float|null $value
     * @return ReturnOrder
     */
    public function setValue(float $value = null): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param int|null $weightInGrams
     * @return ReturnOrder
     */
    public function setWeightInGrams(int $weightInGrams = null): self
    {
        $this->weightInGrams = $weightInGrams;

        return $this;
    }

    /**
     * @param CustomsDocument|null $customsDocument
     * @return ReturnOrder
     */
    public function setCustomsDocument(CustomsDocument $customsDocument = null): self
    {
        $this->customsDocument = $customsDocument;

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
