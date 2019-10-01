<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Http\ClientPlugin;

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
 * @package Dhl\Sdk\Paket\Retoure\Http
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
final class ReturnLabelErrorPlugin implements Plugin
{
    /**
     * Returns the formatted error message.
     *
     * @param ResponseInterface $response
     * @return string
     */
    private function getErrorMessage(ResponseInterface $response): string
    {
        $responseContentTypes = $response->getHeader('Content-Type');
        $contentType = $responseContentTypes[0];
        $errorMessage = $response->getReasonPhrase();

        if ($contentType === 'application/json') {
            $responseJson = (string)$response->getBody();
            $responseData = \json_decode($responseJson, true);
            if (isset($responseData['code'], $responseData['detail'])) {
                $errorMessage = sprintf('%s (Error %s)', $responseData['detail'], $responseData['code']);
            } elseif (isset($responseData['statusCode'], $responseData['statusText'])) {
                $errorMessage = sprintf('%s (Error %s)', $responseData['statusText'], $responseData['statusCode']);
            }
        }

        return $errorMessage;
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
                throw new ClientErrorException($this->getErrorMessage($response), $request, $response);
            } elseif ($statusCode >= 500 && $statusCode < 600) {
                throw new ServerErrorException($this->getErrorMessage($response), $request, $response);
            }

            // no error
            return $response;
        };

        return $promise->then($fnFulfilled);
    }
}
