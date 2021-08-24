<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api\Data;

/**
 * Interface AuthenticationStorageInterface
 *
 * @api
 */
interface AuthenticationStorageInterface
{
    public function getApplicationId(): string;

    public function getApplicationToken(): string;

    public function getUser(): string;

    public function getSignature(): string;
}
