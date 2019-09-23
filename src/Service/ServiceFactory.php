<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Service;

use Dhl\Sdk\Paket\Retoure\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\Paket\Retoure\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\Paket\Retoure\Api\ServiceFactoryInterface;
use Dhl\Sdk\Paket\Retoure\Http\HttpServiceFactory;
use Http\Discovery\HttpClientDiscovery;
use Psr\Log\LoggerInterface;

/**
 * Class ServiceFactory
 *
 * @package Dhl\Sdk\Paket\Retoure\Service
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class ServiceFactory implements ServiceFactoryInterface
{
    /**
     * Create the return label service
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
    ): ReturnLabelServiceInterface {
        $httpClient = HttpClientDiscovery::find();
        $httpServiceFactory = new HttpServiceFactory($httpClient);

        $authService = $httpServiceFactory->createReturnLabelService($authStorage, $logger, $sandboxMode);

        return $authService;
    }
}
