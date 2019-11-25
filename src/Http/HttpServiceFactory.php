<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Http;

use Dhl\Sdk\Paket\Retoure\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\Paket\Retoure\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\Paket\Retoure\Api\ServiceFactoryInterface;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceExceptionFactory;
use Dhl\Sdk\Paket\Retoure\Http\ClientPlugin\ReturnLabelErrorPlugin;
use Dhl\Sdk\Paket\Retoure\Serializer\JsonSerializer;
use Dhl\Sdk\Paket\Retoure\Service\ReturnLabelService;
use Http\Client\Common\Plugin\AuthenticationPlugin;
use Http\Client\Common\Plugin\HeaderAppendPlugin;
use Http\Client\Common\Plugin\LoggerPlugin;
use Http\Client\Common\PluginClient;
use Http\Client\HttpClient;
use Http\Discovery\Exception\NotFoundException;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Discovery\StreamFactoryDiscovery;
use Http\Message\Authentication\BasicAuth;
use Http\Message\Formatter\FullHttpMessageFormatter;
use Psr\Log\LoggerInterface;

/**
 * Class HttpServiceFactory
 *
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

    public function createReturnLabelService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ReturnLabelServiceInterface {
        $authentication = new BasicAuth($authStorage->getApplicationId(), $authStorage->getApplicationToken());
        $userAuthHeader = base64_encode(
            $authStorage->getUser() . ':' . $authStorage->getSignature()
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
            new LoggerPlugin($logger, new FullHttpMessageFormatter(null)),
            new ReturnLabelErrorPlugin()
        ];

        $client = new PluginClient($this->httpClient, $plugins);
        $baseUrl = $sandboxMode ? self::BASE_URL_SANDBOX : self::BASE_URL_PRODUCTION;
        $serializer = new JsonSerializer();

        try {
            $requestFactory = MessageFactoryDiscovery::find();
            $streamFactory = StreamFactoryDiscovery::find();
        } catch (NotFoundException $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        return new ReturnLabelService($client, $baseUrl, $serializer, $requestFactory, $streamFactory);
    }
}
