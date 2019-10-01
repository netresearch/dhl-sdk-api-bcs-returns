<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Exception;

use Http\Client\Exception as HttpClientException;
use Throwable;

/**
 * Class ServiceException
 *
 * Generic SDK exception, can be used to catch any communication exception in
 * cases where the exact type does not matter.
 *
 * @api
 * @package Dhl\Sdk\Paket\Retoure\Exception
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
abstract class ServiceException extends \Exception implements HttpClientException
{
    /**
     * ServiceException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    final public function __construct(
        $message = "",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create service exception.
     *
     * @param \Exception $exception
     * @return static
     */
    public static function create(\Exception $exception): ServiceException
    {
        if ($exception->getCode() === 401) {
            return new AuthenticationException('Authentication failed.', $exception->getCode(), $exception);
        }
        return new static($exception->getMessage(), $exception->getCode(), $exception);
    }

    /**
     * Create service exception from HTTP client exception.
     *
     * @param HttpClientException $exception
     * @return static
     */
    public static function httpClientException(HttpClientException $exception): ServiceException
    {
        if ($exception instanceof \Exception) {
            return new static($exception->getMessage(), $exception->getCode(), $exception);
        }

        return new static('Unknown exception occurred', 0);
    }
}
