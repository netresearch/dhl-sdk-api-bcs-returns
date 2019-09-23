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
 * @package Dhl\Sdk\Paket\Retoure\Model\ResponseType
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class Confirmation implements ConfirmationInterface
{
    /**
     * @return string
     */
    public function getShipmentNumber(): string
    {
        // TODO: Implement getShipmentNumber() method.
        return '';
    }

    /**
     * @return string
     */
    public function getLabelData(): string
    {
        // TODO: Implement getLabelData() method.
        return '';
    }

    /**
     * @return string
     */
    public function getQrLabelData(): string
    {
        // TODO: Implement getQrLabelData() method.
        return '';
    }

    /**
     * @return string
     */
    public function getRoutingCode(): string
    {
        // TODO: Implement getRoutingCode() method.
        return '';
    }
}
