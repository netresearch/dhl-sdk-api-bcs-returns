<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model\ResponseType;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;

/**
 * Class Confirmation
 *
 * @package Dhl\Sdk\Paket\Retoure\Model
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class Confirmation implements ConfirmationInterface
{
    /**
     * @var string
     */
    private $shipmentNumber;

    /**
     * @var string
     */
    private $labelData;

    /**
     * @var string
     */
    private $qrLabelData;

    /**
     * @var string
     */
    private $routingCode;

    /**
     * Confirmation constructor.
     * @param string $shipmentNumber
     * @param string $labelData
     * @param string $qrLabelData
     * @param string $routingCode
     */
    public function __construct(
        string $shipmentNumber,
        string $labelData,
        string $qrLabelData,
        string $routingCode
    ) {
        $this->shipmentNumber = $shipmentNumber;
        $this->labelData = $labelData;
        $this->qrLabelData = $qrLabelData;
        $this->routingCode = $routingCode;
    }

    /**
     * @return string
     */
    public function getShipmentNumber(): string
    {
        return $this->shipmentNumber;
    }

    /**
     * @return string
     */
    public function getLabelData(): string
    {
        return $this->labelData;
    }

    /**
     * @return string
     */
    public function getQrLabelData(): string
    {
        return $this->qrLabelData;
    }

    /**
     * @return string
     */
    public function getRoutingCode(): string
    {
        return $this->routingCode;
    }
}
