<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

/**
 * Interface ReturnLabelRequestBuilderInterface
 *
 * @api
 * @package Dhl\Sdk\Paket\Retoure\Api
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
interface ReturnLabelRequestBuilderInterface
{
    /**
     * Set account related data.
     *
     * The name of the return recipient (receiverId) can be found in the
     * DHL business customer portal. The billing number will be printed on
     * the label.
     *
     * @param string $receiverId Receiver ID (Retourenempfängername)
     * @param string|null $billingNumber Billing Number (Abrechnungsnummer)
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setAccountDetails(
        string $receiverId,
        string $billingNumber = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Set shipment reference (optional).
     *
     * The shipment reference is used to identify a return in the DHL business
     * customer portal listing. It is not printed on the label.
     *
     * @param string $shipmentReference
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setShipmentReference(string $shipmentReference): ReturnLabelRequestBuilderInterface;

    /**
     * Request only PDF shipping label (optional).
     *
     * By default, PDF label and QR code image will be requested.
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setDocumentTypePdf(): ReturnLabelRequestBuilderInterface;

    /**
     * Request only QR code image (optional).
     *
     * By default, PDF label and QR code image will be requested.
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setDocumentTypeQr(): ReturnLabelRequestBuilderInterface;

    /**
     * Set package related data (optional).
     *
     * @param int|null $weightInGrams Total weight of all included items (plus tare weight).
     * @param float|null $amount Total monetary value of all included items.
     *
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setPackageDetails(
        int $weightInGrams = null,
        float $amount = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Set the sender of the return shipment (the consumer).
     *
     * @param string $name
     * @param string $countryCode
     * @param string $postalCode
     * @param string $city
     * @param string $streetName
     * @param string $streetNumber
     * @param string|null $company
     * @param string|null $nameAddition
     * @param string|null $state
     * @param string|null $countryName
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setShipperAddress(
        string $name,
        string $countryCode,
        string $postalCode,
        string $city,
        string $streetName,
        string $streetNumber,
        string $company = null,
        string $nameAddition = null,
        string $state = null,
        string $countryName = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Set customer contact (optional).
     *
     * @param string $email
     * @param string|null $phoneNumber
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setContact(string $email, string $phoneNumber = null): ReturnLabelRequestBuilderInterface;

    /**
     * Set customs details, mandatory if customs form ("CN23") is required.
     *
     * @param string $currency Currency the returned goods were payed in.
     * @param string|null $originalShipmentNumber Original shipment number.
     * @param string|null $originalOperator Company that delivered the original parcel.
     * @param string|null $accompanyingDocument Additional documents.
     * @param string|null $originalInvoiceNumber Invoice number of the returned goods.
     * @param string|null $originalInvoiceDate Date of the invoice.
     * @param string|null $comment
     * @return ReturnLabelRequestBuilderInterface
     */
    public function setCustomsDetails(
        string $currency,
        string $originalShipmentNumber = null,
        string $originalOperator = null,
        string $accompanyingDocument = null,
        string $originalInvoiceNumber = null,
        string $originalInvoiceDate = null,
        string $comment = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Add an item to be declared, mandatory if customs form ("CN23") is required.
     *
     * @param int $qty Amount of items declared per position.
     * @param string $description Description of the returned item.
     * @param float $value Monetary value of returned item.
     * @param int $weightInGrams Weight of the returned item.
     * @param string $sku Article reference of the returned item.
     * @param string|null $countryOfOrigin Country the returned item was produced.
     * @param string|null $tariffNumber Customs tariff number.
     * @return ReturnLabelRequestBuilderInterface
     */
    public function addCustomsItem(
        int $qty,
        string $description,
        float $value,
        int $weightInGrams,
        string $sku,
        string $countryOfOrigin = null,
        string $tariffNumber = null
    ): ReturnLabelRequestBuilderInterface;

    /**
     * Create the return label request and reset the builder data.
     *
     * @return \JsonSerializable
     */
    public function create(): \JsonSerializable;
}
