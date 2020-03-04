<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Exception;

/**
 * Class RequestValidatorException
 *
 * A special instance of the DetailedServiceException which is
 * caused by invalid request data before a web service request was sent.
 *
 * @api
 * @author Andreas Müller <andreas.mueller@netresearch.de>
 * @link   https://netresearch.de
 */
class RequestValidatorException extends DetailedServiceException
{
}
