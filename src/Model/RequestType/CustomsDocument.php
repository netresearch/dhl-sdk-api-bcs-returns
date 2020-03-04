<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\RequestType;

/**
 * Class CustomsDocument
 *
 * @author Andreas Müller <andreas.mueller@netresearch.de>
 * @link   https://www.netresearch.de/
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

    public function setOriginalShipmentNumber(?string $originalShipmentNumber): self
    {
        $this->originalShipmentNumber = $originalShipmentNumber;

        return $this;
    }

    public function setOriginalOperator(?string $originalOperator): self
    {
        $this->originalOperator = $originalOperator;

        return $this;
    }

    public function setAccompanyingDocument(?string $accompanyingDocument): self
    {
        $this->acommpanyingDocument = $accompanyingDocument;

        return $this;
    }

    public function setOriginalInvoiceNumber(?string $originalInvoiceNumber): self
    {
        $this->originalInvoiceNumber = $originalInvoiceNumber;

        return $this;
    }
    public function setOriginalInvoiceDate(?string $originalInvoiceDate): self
    {
        $this->originalInvoiceDate = $originalInvoiceDate;

        return $this;
    }

    public function setComment(?string $comment): self
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
