<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class CustomsDocument
 *
 * @package Dhl\Sdk\Paket\Retoure\Model
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class CustomsDocument implements \JsonSerializable
{
    /**
     * @var string $currency Currency the returned goods were payed in: [EUR, GBP, CHF]
     */
    private $currency;

    /**
     * @var CustomsDocumentPosition[] $positions The customs items to be declared.
     */
    private $positions;

    /**
     * @var string|null $originalShipmentNumber Original shipment number.
     */
    private $originalShipmentNumber;

    /**
     * @var string|null $originalOperator Company that delivered the original parcel.
     */
    private $originalOperator;

    /**
     * @var string|null $acommpanyingDocument Additional documents.
     */
    private $acommpanyingDocument;

    /**
     * @var string|null $originalInvoiceNumber Invoice number of the returned goods.
     */
    private $originalInvoiceNumber;

    /**
     * @var string|null $originalInvoiceDate Date of the invoice.
     */
    private $originalInvoiceDate;

    /**
     * @var string|null $comment Comment.
     */
    private $comment;

    /**
     * CustomsDocument constructor.
     * @param string $currency
     * @param CustomsDocumentPosition[] $positions
     */
    public function __construct(
        string $currency,
        array $positions
    ) {
        $this->currency = $currency;
        $this->positions = $positions;
    }

    /**
     * @param string|null $originalShipmentNumber
     * @return CustomsDocument
     */
    public function setOriginalShipmentNumber(string $originalShipmentNumber = null): self
    {
        $this->originalShipmentNumber = $originalShipmentNumber;

        return $this;
    }

    /**
     * @param string|null $originalOperator
     * @return CustomsDocument
     */
    public function setOriginalOperator(string $originalOperator = null): self
    {
        $this->originalOperator = $originalOperator;

        return $this;
    }

    /**
     * @param string|null $accompanyingDocument
     * @return CustomsDocument
     */
    public function setAccompanyingDocument(string $accompanyingDocument = null): self
    {
        $this->acommpanyingDocument = $accompanyingDocument;

        return $this;
    }

    /**
     * @param string|null $originalInvoiceNumber
     * @return CustomsDocument
     */
    public function setOriginalInvoiceNumber(string $originalInvoiceNumber = null): self
    {
        $this->originalInvoiceNumber = $originalInvoiceNumber;

        return $this;
    }

    /**
     * @param string|null $originalInvoiceDate
     * @return CustomsDocument
     */
    public function setOriginalInvoiceDate(string $originalInvoiceDate = null): self
    {
        $this->originalInvoiceDate = $originalInvoiceDate;

        return $this;
    }

    /**
     * @param string|null $comment
     * @return CustomsDocument
     */
    public function setComment(string $comment = null): self
    {
        $this->comment = $comment;

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
