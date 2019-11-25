<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Test\Provider;

use Dhl\Sdk\Paket\Retoure\Exception\RequestValidatorException;
use Dhl\Sdk\Paket\Retoure\Model\ReturnLabelRequestBuilder;

/**
 * Class ReturnLabelRequestProvider
 *
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class ReturnLabelRequestProvider
{
    /**
     * Build a valid request for a domestic return label. Configurable return document type.
     *
     * @param string|null $qrCode
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public static function validRequest(string $qrCode = null): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setAccountDetails('DE', '22222222220701');

        if ($qrCode === 'QR') {
            $requestBuilder->setDocumentTypeQr();
        }
        if ($qrCode === 'PDF') {
            $requestBuilder->setDocumentTypePdf();
        }

        $requestBuilder->setShipperContact('tester@nettest.eu');
        $requestBuilder->setShipperAddress(
            'Test Tester',
            'DEU',
            '04229',
            'Leipzig',
            'Nonnenstrasse',
            '11'
        );

        return $requestBuilder->create();
    }

    /**
     * Build a valid request for a return label which requires a customs document.
     *
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public static function validCustomsRequest(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setAccountDetails('CH', '22222222225301');

        $requestBuilder->setShipperContact('tester@nettest.eu');
        $requestBuilder->setShipperAddress(
            'Test Tester',
            'CHE',
            '8005',
            'Zürich',
            'Lagerstrasse',
            '11'
        );
        $requestBuilder->setCustomsDetails('EUR', '000000052', 'dhlpaket');
        $requestBuilder->addCustomsItem(
            3,
            'DHL Foo',
            59,
            800,
            '24-MB01',
            'DEU',
            '123456'
        );
        $requestBuilder->addCustomsItem(
            1,
            'DHL Bar',
            29.95,
            1200,
            '24-MB02',
            'CHN',
            '654321'
        );

        return $requestBuilder->create();
    }

    /**
     * Set an invalid country code as origin country to trigger a validation error response (400 Bad Request).
     *
     * @return \JsonSerializable
     * @throws RequestValidatorException
     */
    public static function validationErrorRequest(): \JsonSerializable
    {
        $requestBuilder = new ReturnLabelRequestBuilder();
        $requestBuilder->setAccountDetails('CH', '22222222225301');

        $requestBuilder->setShipperContact('tester@nettest.eu');
        $requestBuilder->setShipperAddress(
            'Test Tester',
            'CHE',
            '8005',
            'Zürich',
            'Lagerstrasse',
            '11'
        );
        $requestBuilder->setCustomsDetails('EUR', '000000052', 'dhlpaket');
        $requestBuilder->addCustomsItem(
            1,
            'DHL Test Description',
            59,
            800,
            '24-MB02',
            'XXX', // invalid, must be three-letter iso code
            '123456'
        );

        return $requestBuilder->create();
    }
}
