<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Service\ReturnLabelService;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;

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

    public function getShipmentNumber(): string
    {
        return $this->shipmentNumber;
    }

    public function getLabelData(): string
    {
        return $this->labelData;
    }

    public function getQrLabelData(): string
    {
        return $this->qrLabelData;
    }

    public function getRoutingCode(): string
    {
        return $this->routingCode;
    }
}
