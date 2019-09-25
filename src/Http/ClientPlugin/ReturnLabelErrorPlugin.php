<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Http\ClientPlugin;

use Dhl\Sdk\Paket\Retoure\Exception\AuthenticationException;
use Dhl\Sdk\Paket\Retoure\Exception\ClientException;
use Http\Client\Common\Exception\ClientErrorException;
use Http\Client\Common\Exception\ServerErrorException;
use Http\Client\Common\Plugin;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ReturnLabelErrorPlugin
 *
 * On request errors, throw an HTTP exception with message extracted from response.
 * @package Dhl\Sdk\Paket\Retoure\Http\ClientPlugin
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
final class ReturnLabelErrorPlugin implements Plugin
{
    /**
     * @param int $statusCode
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @throws AuthenticationException
     * @throws ClientException
     */
    private function handleClientError(int $statusCode, RequestInterface $request, ResponseInterface $response)
    {
        $responseJson = (string) $response->getBody();
        if (!$responseJson || !\in_array($statusCode, [400, 401, 403], true)) {
            // throw generic client exception
            throw new ClientErrorException($response->getReasonPhrase(), $request, $response);
        }

        $responseData = \json_decode($responseJson, true);

        if ($statusCode === 400) {
            throw new ClientException(
                $this->formatErrorMessage($statusCode, $responseData),
                $statusCode
            );
        }
        if (\in_array($statusCode, [401, 403], true)) {
            throw new AuthenticationException(
                $this->formatErrorMessage($statusCode, $responseData),
                $statusCode
            );
        }
    }

    /**
     * @param int $statusCode
     * @param RequestInterface $request
     * @param ResponseInterface $response
     *
     * @throws ServerErrorException
     */
    private function handleServerError(int $statusCode, RequestInterface $request, ResponseInterface $response)
    {
        $responseJson = (string) $response->getBody();
        if (!$responseJson || !\in_array($statusCode, [500, 503], true)) {
            // throw generic server exception
            throw new ServerErrorException($response->getReasonPhrase(), $request, $response);
        }

        $responseData = \json_decode($responseJson, true);
        if ($statusCode === 500) {
            // throw internal service error
            $message = $responseData['detail'] ?? $responseData['code'];
            throw new ServerErrorException($message, $request, $response);
        }

        if ($statusCode === 503) {
            // throw service unavailable error
            throw new ServerErrorException(
                $this->formatErrorMessage($statusCode, $responseData),
                $request,
                $response
            );
        }
    }

    /**
     * Returns the formatted error message.
     *
     * @param int $statusCode
     * @param string[] $responseData
     * @return string
     */
    private function formatErrorMessage(int $statusCode, array $responseData): string
    {
        $errorMessage = 'Details: %s, Status: %d';
        $errorDetail = $responseData['detail'] ?? '';

        return sprintf(
            $errorMessage,
            $errorDetail,
            $statusCode
        );
    }

    /**
     * Handle the request and return the response coming from the next callable.
     *
     * @param RequestInterface $request
     * @param callable $next Next middleware in the chain, the request is passed as the first argument
     * @param callable $first First middleware in the chain, used to to restart a request
     *
     * @return Promise Resolves a PSR-7 Response or fails with an Http\Client\Exception (The same as HttpAsyncClient).
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        /** @var Promise $promise */
        $promise = $next($request);
        $fnFulfilled = function (ResponseInterface $response) use ($request) {
            $statusCode = $response->getStatusCode();

            if ($statusCode >= 400 && $statusCode < 500) {
                $this->handleClientError($statusCode, $request, $response);
            } elseif ($statusCode >= 500 && $statusCode < 600) {
                $this->handleServerError($statusCode, $request, $response);
            }

            // no error
            return $response;
        };

        return $promise->then($fnFulfilled);
    }
}
