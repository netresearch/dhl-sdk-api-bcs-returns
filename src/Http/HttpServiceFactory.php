<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Http;

use Dhl\Sdk\Paket\Retoure\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\Paket\Retoure\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\Paket\Retoure\Api\ServiceFactoryInterface;
use Dhl\Sdk\Paket\Retoure\Serializer\JsonSerializer;
use Dhl\Sdk\Paket\Retoure\Service\ReturnLabelService;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\ErrorPlugin;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Log\LoggerInterface;

/**
 * Class HttpServiceFactory
 *
 * @package Dhl\Sdk\Paket\Retoure\Http
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de
 */
class HttpServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * HttpServiceFactory constructor.
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param AuthenticationStorageInterface $authStorage
     * @param LoggerInterface $logger
     * @param bool $sandboxMode
     *
     * @return ReturnLabelServiceInterface
     */
    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface {
        $authentication = new BasicAuth($authStorage->getUser(), $authStorage->getSignature());
        $userAuthHeader = base64_encode(
            $authStorage->getApplicationId() . ':' . $authStorage->getApplicationToken()
        );
        $plugins = [
            new HeaderAppendPlugin(
                [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'DPDHL-User-Authentication-Token' => $userAuthHeader
                ]
            ),
            new AuthenticationPlugin($authentication),
            new LoggerPlugin($logger, new FullHttpMessageFormatter(4096)),
            new ErrorPlugin()
        ];
        $client = new PluginClient($this->httpClient, $plugins);
        $baseUrl = $sandboxMode ? self::BASE_URL_SANDBOX : self::BASE_URL_PRODUCTION;
        $serializer = new JsonSerializer();
        $requestFactory = MessageFactoryDiscovery::find();
        $streamFactory = StreamFactoryDiscovery::find();

        return new ReturnLabelService($client, $baseUrl, $serializer, $requestFactory, $streamFactory);
    }
}
