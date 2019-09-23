<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceException;

/**
 * Interface ReturnLabelServiceInterface
 *
 * @api
 * @package Dhl\Sdk\Paket\Retoure\Api
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
interface ReturnLabelServiceInterface
{
    /**
     * BookLabel is the operation call used to generate return labels.
     *
     * @param \JsonSerializable $returnOrder
     *
     * @return ConfirmationInterface
     *
     * @throws ServiceException
     */
    public function bookLabel(\JsonSerializable $returnOrder): ConfirmationInterface;
}
