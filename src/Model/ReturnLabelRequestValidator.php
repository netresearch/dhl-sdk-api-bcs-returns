<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model;

use Dhl\Sdk\Paket\Retoure\Exception\RequestValidatorException;

/**
 * Class ReturnLabelRequestValidator
 *
 * @author Andreas Müller <andreas.mueller@netresearch.de>
 * @link   https://www.netresearch.de/
 */
class ReturnLabelRequestValidator
{
    public const MSG_RECEIVER_ID_REQUIRED = 'Receiver ID is required.';
    public const MSG_SHIPPER_ADDRESS_REQUIRED = 'Shipper address is required.';
    public const MSG_SHIPPER_ADDRESS_FIELD_REQUIRED = "'%s' is required for the shipper address.";
    public const MSG_COUNTRY_ISO_INVALID = 'Only ISO 3166-1 alpha-3 country codes are allowed, e.g. "DEU".';
    public const MSG_CURRENCY_INVALID = 'Only EUR, GBP or CHF currency is allowed.';
    public const MSG_CUSTOMS_POSITIONS_COUNT = 'Between 1 and 5 customs items must be added.';
    public const MSG_CUSTOMS_POSITION_FIELD_REQUIRED = "'%s' is required for the customs item.";

    /**
     * Validate request data before sending it to the web service.
     *
     * @param mixed[][] $data
     *
     * @throws RequestValidatorException
     */
    public static function validate(array $data): void
    {
        if (empty($data['receiverId'])) {
            throw new RequestValidatorException(self::MSG_RECEIVER_ID_REQUIRED);
        }

        if (empty($data['shipper']['address'])) {
            throw new RequestValidatorException(self::MSG_SHIPPER_ADDRESS_REQUIRED);
        }

        $addressRequired = ['name', 'countryCode', 'postalCode', 'city', 'streetName', 'streetNumber'];
        foreach ($addressRequired as $fieldName) {
            if (empty($data['shipper']['address'][$fieldName])) {
                throw new RequestValidatorException(sprintf(self::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED, $fieldName));
            }
        }

        if (strlen($data['shipper']['address']['countryCode']) !== 3) {
            throw new RequestValidatorException(self::MSG_COUNTRY_ISO_INVALID);
        }

        if (!isset($data['customsDetails'])) {
            // nothing more to validate
            return;
        }

        $customs = $data['customsDetails'];
        if (empty($customs['currency']) || !in_array($customs['currency'], ['EUR', 'GBP', 'CHF'])) {
            throw new RequestValidatorException(self::MSG_CURRENCY_INVALID);
        }

        if (empty($customs['items']) || !is_array($customs['items']) || count($customs['items']) > 5) {
            throw new RequestValidatorException(self::MSG_CUSTOMS_POSITIONS_COUNT);
        }

        $itemRequired = ['description', 'qty', 'weight', 'sku', 'value'];
        foreach ($customs['items'] as $customsItem) {
            foreach ($itemRequired as $fieldName) {
                if (empty($customsItem[$fieldName])) {
                    throw new RequestValidatorException(sprintf(self::MSG_CUSTOMS_POSITION_FIELD_REQUIRED, $fieldName));
                }
            }

            if (!empty($customsItem['countryOfOrigin']) && strlen($customsItem['countryOfOrigin']) !== 3) {
                throw new RequestValidatorException(self::MSG_COUNTRY_ISO_INVALID);
            }
        }
    }
}
