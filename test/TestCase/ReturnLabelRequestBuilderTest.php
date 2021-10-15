<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Model;

use Dhl\Sdk\Paket\Retoure\Exception\RequestValidatorException;
use Dhl\Sdk\Paket\Retoure\Model\ReturnLabelRequestValidator as Validator;

class ReturnLabelRequestBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return ReturnLabelRequestBuilder[][]
     */
    public function dataProvider(): array
    {
        $missingReceiverIdBuilder = new ReturnLabelRequestBuilder();
        $missingReceiverIdBuilder->setAccountDetails('');
        $missingReceiverIdBuilder->setShipperAddress('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');

        $wrongCountryBuilder = new ReturnLabelRequestBuilder();
        $wrongCountryBuilder->setAccountDetails('DE');
        $wrongCountryBuilder->setShipperAddress('Test Tester', 'DE', '04229', 'Leipzig', 'Klingerweg', '6');

        $missingStreetNumberBuilder = new ReturnLabelRequestBuilder();
        $missingStreetNumberBuilder->setAccountDetails('DE');
        $missingStreetNumberBuilder->setShipperAddress('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '');

        $invalidCurrencyBuilder = new ReturnLabelRequestBuilder();
        $invalidCurrencyBuilder->setAccountDetails('CH');
        $invalidCurrencyBuilder->setShipperAddress('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $invalidCurrencyBuilder->setCustomsDetails('SFR');
        $invalidCurrencyBuilder->addCustomsItem(3, 'DHL Foo', 59, 800, '24-MB01', 'DEU', '123456');

        $missingCustomsBuilder = new ReturnLabelRequestBuilder();
        $missingCustomsBuilder->setAccountDetails('CH');
        $missingCustomsBuilder->setShipperAddress('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $missingCustomsBuilder->addCustomsItem(3, 'DHL Foo', 59, 800, '24-MB01', 'DEU', '123456');

        $missingPositionsBuilder = new ReturnLabelRequestBuilder();
        $missingPositionsBuilder->setAccountDetails('CH');
        $missingPositionsBuilder->setShipperAddress('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $missingPositionsBuilder->setCustomsDetails('CHF');

        $tooManyPositionsBuilder = new ReturnLabelRequestBuilder();
        $tooManyPositionsBuilder->setAccountDetails('CH');
        $tooManyPositionsBuilder->setShipperAddress('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $tooManyPositionsBuilder->setCustomsDetails('CHF');
        $tooManyPositionsBuilder->addCustomsItem(1, 'DHL Foo', 59, 800, '24-MB01', 'DEU', '123456');
        $tooManyPositionsBuilder->addCustomsItem(2, 'DHL Foo', 59, 800, '24-MB02', 'DEU', '123456');
        $tooManyPositionsBuilder->addCustomsItem(3, 'DHL Foo', 59, 800, '24-MB03', 'DEU', '123456');
        $tooManyPositionsBuilder->addCustomsItem(4, 'DHL Foo', 59, 800, '24-MB04', 'DEU', '123456');
        $tooManyPositionsBuilder->addCustomsItem(5, 'DHL Foo', 59, 800, '24-MB05', 'DEU', '123456');
        $tooManyPositionsBuilder->addCustomsItem(6, 'DHL Foo', 59, 800, '24-MB06', 'DEU', '123456');

        $wrongOriginBuilder = new ReturnLabelRequestBuilder();
        $wrongOriginBuilder->setAccountDetails('CH');
        $wrongOriginBuilder->setShipperAddress('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $wrongOriginBuilder->setCustomsDetails('CHF');
        $wrongOriginBuilder->addCustomsItem(1, 'DHL Foo', 59, 800, '24-MB01', 'DE', '123456');

        return [
            'missing_receiver_id' => [$missingReceiverIdBuilder, Validator::MSG_RECEIVER_ID_REQUIRED],
            'shipper_country_iso' => [$wrongCountryBuilder, Validator::MSG_COUNTRY_ISO_INVALID],
            'missing_street_number' => [$missingStreetNumberBuilder, Validator::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED],
            'invalid_currency_code' => [$invalidCurrencyBuilder, Validator::MSG_CURRENCY_INVALID],
            'customs_details_missing' => [$missingCustomsBuilder, Validator::MSG_CURRENCY_INVALID],
            'customs_positions_missing' => [$missingPositionsBuilder, Validator::MSG_CUSTOMS_POSITIONS_COUNT],
            'customs_position_exceeds_max' => [$tooManyPositionsBuilder, Validator::MSG_CUSTOMS_POSITIONS_COUNT],
            'customs_position_country_iso' => [$wrongOriginBuilder, Validator::MSG_COUNTRY_ISO_INVALID],
        ];
    }

    /**
     * Assert valid request is build properly.
     *
     * @test
     * @throws RequestValidatorException
     */
    public function validRequest()
    {
        $builder = new ReturnLabelRequestBuilder();
        $builder->setAccountDetails($receiverId = 'CH', $customerReference = '22222222225301');
        $builder->setShipmentReference($shipmentReference = 'RMA #1');
        $builder->setDocumentTypePdf();
        $builder->setShipperAddress(
            $shipperName = 'Test Tester',
            $shipperCountry = 'CHE',
            $shipperPostalCode = '8005',
            $shipperCity = 'Zürich',
            $shipperStreetName = 'Lagerstrasse',
            $shipperStreetNumber = '10'
        );
        $builder->setShipperContact($email = 'tester@nettest.eu', $phone = '+00 1337');
        $builder->setPackageDetails($weight = 4200, $amount = 295.0);
        $builder->setCustomsDetails($currency = 'CHF');
        $builder->addCustomsItem(1, 'DHL Foo', 59, 800, '24-MB01', 'DEU', '123456');
        $builder->addCustomsItem(2, 'DHL Foo', 59, 800, '24-MB02', 'DEU', '123456');
        $builder->addCustomsItem(3, 'DHL Foo', 59, 800, '24-MB03', 'DEU', '123456');
        $builder->addCustomsItem(4, 'DHL Foo', 59, 800, '24-MB04', 'DEU', '123456');
        $builder->addCustomsItem(5, 'DHL Foo', 59, 800, '24-MB05', 'DEU', '123456');

        $request = $builder->create();
        $requestJson = json_encode($request, JSON_UNESCAPED_UNICODE);

        self::assertNotFalse(strpos($requestJson, "\"receiverId\":\"{$receiverId}\""));
        self::assertNotFalse(strpos($requestJson, "\"customerReference\":\"{$customerReference}\""));
        self::assertNotFalse(strpos($requestJson, "\"shipmentReference\":\"{$shipmentReference}\""));
        self::assertNotFalse(strpos($requestJson, "\"returnDocumentType\":\"SHIPMENT_LABEL\""));
        self::assertNotFalse(strpos($requestJson, "\"email\":\"{$email}\""));
        self::assertNotFalse(strpos($requestJson, "\"telephoneNumber\":\"{$phone}\""));
        self::assertNotFalse(strpos($requestJson, "\"name1\":\"{$shipperName}\""));
        self::assertNotFalse(strpos($requestJson, "\"countryISOCode\":\"{$shipperCountry}\""));
        self::assertNotFalse(strpos($requestJson, "\"postCode\":\"{$shipperPostalCode}\""));
        self::assertNotFalse(strpos($requestJson, "\"city\":\"{$shipperCity}\""));
        self::assertNotFalse(strpos($requestJson, "\"streetName\":\"{$shipperStreetName}\""));
        self::assertNotFalse(strpos($requestJson, "\"houseNumber\":\"{$shipperStreetNumber}\""));
        self::assertNotFalse(strpos($requestJson, "\"weightInGrams\":{$weight}"));
        self::assertNotFalse(strpos($requestJson, "\"value\":{$amount}"));
        self::assertNotFalse(strpos($requestJson, "\"currency\":\"{$currency}\""));
    }

    /**
     * Assert invalid requests throw RequestValidatorException.
     *
     * @test
     * @dataProvider dataProvider
     * @param ReturnLabelRequestBuilder $builder
     * @param string $exceptionMessage
     * @throws RequestValidatorException
     */
    public function invalidRequest(ReturnLabelRequestBuilder $builder, string $exceptionMessage)
    {
        $this->expectException(RequestValidatorException::class);
        if (strpos($exceptionMessage, '%s') !== false) {
            $exceptionMessage = str_replace('%s', '[\w]+', $exceptionMessage);
            $this->assertNotFalse(strpos("/$exceptionMessage/", $exceptionMessage));
        } else {
            $this->expectExceptionMessage($exceptionMessage);
        }

        $builder->create();
    }
}
