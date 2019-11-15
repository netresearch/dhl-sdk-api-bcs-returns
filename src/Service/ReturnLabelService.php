<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Service;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;
use Dhl\Sdk\Paket\Retoure\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\Paket\Retoure\Exception\AuthenticationErrorException;
use Dhl\Sdk\Paket\Retoure\Exception\DetailedErrorException;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceExceptionFactory;
use Dhl\Sdk\Paket\Retoure\Model\ResponseType\Confirmation;
use Dhl\Sdk\Paket\Retoure\Serializer\JsonSerializer;
use Http\Client\Exception as HttpClientException;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Http\Message\StreamFactory;

/**
 * Class ReturnLabelService
 *
 * @package Dhl\Sdk\Paket\Retoure\Service
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class ReturnLabelService implements ReturnLabelServiceInterface
{
    const OPERATION_BOOK_LABEL = 'returns/';

    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var StreamFactory
     */
    private $streamFactory;

    /**
     * ReturnLabelService constructor.
     *
     * @param HttpClient $client
     * @param string $baseUrl
     * @param JsonSerializer $serializer
     * @param RequestFactory $requestFactory
     * @param StreamFactory $streamFactory
     */
    public function __construct(
        HttpClient $client,
        string $baseUrl,
        JsonSerializer $serializer,
        RequestFactory $requestFactory,
        StreamFactory $streamFactory
    ) {
        $this->client = $client;
        $this->baseUrl = $baseUrl;
        $this->serializer = $serializer;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
    }

    public function bookLabel(\JsonSerializable $returnOrder): ConfirmationInterface
    {
        $uri = sprintf('%s/%s', $this->baseUrl, self::OPERATION_BOOK_LABEL);

        try {
            $payload = $this->serializer->encode($returnOrder);
            $stream = $this->streamFactory->createStream($payload);

            $httpRequest = $this->requestFactory->createRequest('POST', $uri);
            $httpRequest = $httpRequest->withBody($stream);

            $response = $this->client->sendRequest($httpRequest);
            $responseJson = (string) $response->getBody();
        } catch (AuthenticationErrorException $exception) {
            throw ServiceExceptionFactory::createAuthenticationException($exception);
        } catch (DetailedErrorException $exception) {
            throw ServiceExceptionFactory::createDetailedServiceException($exception);
        } catch (HttpClientException $exception) {
            throw ServiceExceptionFactory::createServiceException($exception);
        } catch (\Throwable $exception) {
            throw ServiceExceptionFactory::create($exception);
        }

        $responseData = $this->serializer->decode($responseJson);

        $shipmentNumber = $responseData['shipmentNumber'] ?: '';
        $labelData = $responseData['labelData'] ?: '';
        $qrLabelData = $responseData['qrLabelData'] ?: '';
        $routingCode = $responseData['routingCode'] ?: '';

        $confirmation = new Confirmation($shipmentNumber, $labelData, $qrLabelData, $routingCode);

        return $confirmation;
    }
}
