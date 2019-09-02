<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

use Dhl\Sdk\Paket\Retoure\Api\Data\AuthenticationStorageInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface ServiceFactoryInterface
 *
 * @api
 * @package Dhl\Sdk\Paket\Retoure\Api
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
interface ServiceFactoryInterface
{
    const BASE_URL_PRODUCTION = 'https://cig.dhl.de/services/production/rest';
    const BASE_URL_SANDBOX = 'https://cig.dhl.de/services/sandbox/rest';

    const OPERATION_BOOK_LABEL = 'returns';

    /**
     * Create the return service able to perform return label requests.
     *
     * @param AuthenticationStorageInterface $authStorage
     * @param LoggerInterface $logger
     * @param bool $sandboxMode
     * @return ReturnLabelServiceInterface
     */
    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface;
}
