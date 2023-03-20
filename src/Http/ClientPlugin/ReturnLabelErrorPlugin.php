<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Http\ClientPlugin;

use Dhl\Sdk\Paket\Retoure\Exception\AuthenticationErrorException;
use Dhl\Sdk\Paket\Retoure\Exception\DetailedErrorException;
use Http\Client\Common\Plugin;
use Http\Client\Exception\HttpException;
use Http\Promise\Promise;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ReturnLabelErrorPlugin
 *
 * On request errors, throw an HTTP exception with message extracted from response.
 */
final class ReturnLabelErrorPlugin implements Plugin
{
    /**
     * Returns TRUE if the response contains a detailed error response.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isDetailedErrorResponse(ResponseInterface $response): bool
    {
        $contentTypes = $response->getHeader('Content-Type');
        return $contentTypes && ($contentTypes[0] === 'application/json');
    }

    /**
     * Try to extract the error message from the response. If not possible, return default message.
     *
     * @param string[] $responseData
     * @param string $defaultMessage
     * @return string
     */
    private function createErrorMessage(array $responseData, string $defaultMessage): string
    {
        if (isset($responseData['statusCode'], $responseData['statusText'])) {
            return sprintf('%s (Error %s)', $responseData['statusText'], $responseData['statusCode']);
        }
        if (isset($responseData['code'], $responseData['detail'])) {
            return sprintf('%s (Error %s)', $responseData['detail'], $responseData['code']);
        }
        return $defaultMessage;
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

            if (!$this->isDetailedErrorResponse($response)) {
                if ($statusCode === 401 || $statusCode === 403) {
                    $errorMessage = 'Authentication failed. Please check your access credentials.';
                    throw new AuthenticationErrorException($errorMessage, $request, $response);
                }

                if ($statusCode >= 400 && $statusCode < 600) {
                    throw new HttpException($response->getReasonPhrase(), $request, $response);
                }
            } else {
                $responseJson = (string)$response->getBody();
                $responseData = \json_decode($responseJson, true) ?: [];
                $errorMessage = $this->createErrorMessage($responseData, $response->getReasonPhrase());

                if ($statusCode === 401 || $statusCode === 403) {
                    throw new AuthenticationErrorException($errorMessage, $request, $response);
                }

                if ($statusCode >= 400 && $statusCode < 600) {
                    throw new DetailedErrorException($errorMessage, $request, $response);
                }
            }

            // no error
            return $response;
        };

        return $promise->then($fnFulfilled);
    }
}
