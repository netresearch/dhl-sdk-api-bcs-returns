<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Service;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;
use Dhl\Sdk\Paket\Retoure\Api\ReturnLabelServiceInterface;
use Dhl\Sdk\Paket\Retoure\Exception\ClientException;
use Dhl\Sdk\Paket\Retoure\Exception\ServerException;
use Dhl\Sdk\Paket\Retoure\Exception\ServiceException;
use Dhl\Sdk\Paket\Retoure\Model\ResponseType\Confirmation;
use Dhl\Sdk\Paket\Retoure\Serializer\JsonSerializer;
use Http\Client\Common\Exception\ClientErrorException;
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
    const OPERATION_BOOK_LABEL = 'returns';

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

    /**
     * @param \JsonSerializable $returnOrder
     * @return ConfirmationInterface
     *
     * @throws ServiceException
     */
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
        } catch (ClientErrorException $exception) {
            throw ClientException::create($exception);
        } catch (HttpClientException $exception) {
            throw  ServerException::httpClientException($exception);
        } catch (\Exception $exception) {
            throw ServerException::create($exception);
        }

        // TODO respone handling
        $confirmation = new Confirmation();
        return $confirmation;
    }
}
