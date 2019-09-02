<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Api;

/**
 * Interface ReturnLabelRequestBuilderInterface
 *
 * @api
 * @package Dhl\Sdk\Paket\Retoure\Api
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
interface ReturnLabelRequestBuilderInterface
{
    /**
     * Create the return label request and reset the builder data.
     *
     * @return \JsonSerializable
     */
    public function create(): \JsonSerializable;
}
