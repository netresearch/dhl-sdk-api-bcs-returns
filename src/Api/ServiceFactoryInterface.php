<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

use Dhl\Sdk\Paket\Retoure\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceException;
use Psr\Log\LoggerInterface;

/**
 * Interface ServiceFactoryInterface
 *
 * @api
 */
interface ServiceFactoryInterface
{
    public const BASE_URL_PRODUCTION = 'https://cig.dhl.de/services/production/rest';
    public const BASE_URL_SANDBOX = 'https://cig.dhl.de/services/sandbox/rest';

    /**
     * Create the service able to perform return shipment label requests.
     *
     * @param AuthenticationStorageInterface $authStorage
     * @param LoggerInterface $logger
     * @param bool $sandboxMode
     *
     * @return ReturnLabelServiceInterface
     *
     * @throws ServiceException
     */
    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface;
}
