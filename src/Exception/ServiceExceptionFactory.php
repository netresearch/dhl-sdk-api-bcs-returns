<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Exception;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * Class ServiceExceptionFactory
 *
 * A service exception factory to create specific exception instances.
 */
class ServiceExceptionFactory
{
    /**
     * Create a service exception.
     *
     * @param \Throwable $exception
     * @return ServiceException
     */
    public static function create(\Throwable $exception): ServiceException
    {
        return new ServiceException($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Create a HTTP client exception.
     *
     * @param ClientExceptionInterface $exception
     * @return ServiceException
     */
    public static function createServiceException(ClientExceptionInterface $exception): ServiceException
    {
        if (!$exception instanceof \Throwable) {
            return new ServiceException('Unknown exception occurred', 0);
        }

        return self::create($exception);
    }

    /**
     * Create a detailed service exception.
     *
     * @param \Throwable $exception
     * @return DetailedServiceException
     */
    public static function createDetailedServiceException(\Throwable $exception): DetailedServiceException
    {
        return new DetailedServiceException($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Create an authentication exception.
     *
     * @param \Throwable $exception
     * @return AuthenticationException
     */
    public static function createAuthenticationException(\Throwable $exception): AuthenticationException
    {
        return new AuthenticationException($exception->getMessage(), $exception->getCode(), $exception);
    }
}
