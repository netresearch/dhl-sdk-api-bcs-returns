<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;
use Dhl\Sdk\Paket\Retoure\Exception\AuthenticationException;
use Dhl\Sdk\Paket\Retoure\Exception\DetailedServiceException;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceException;

/**
 * Interface ReturnLabelServiceInterface
 *
 * @api
 * @author Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link   https://www.netresearch.de/
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
     * @throws AuthenticationException
     * @throws DetailedServiceException
     * @throws ServiceException
     */
    public function bookLabel(\JsonSerializable $returnOrder): ConfirmationInterface;
}
