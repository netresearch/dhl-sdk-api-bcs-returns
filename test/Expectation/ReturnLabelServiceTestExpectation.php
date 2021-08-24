<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Test\Expectation;

use Dhl\Sdk\Paket\Retoure\Api\Data\ConfirmationInterface;
use Dhl\Sdk\Paket\Retoure\Model\RequestType\ReturnOrder;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;
use Psr\Log\Test\TestLogger;

class ReturnLabelServiceTestExpectation
{
    /**
     * Assert that the properties in the request object match the serialized request payload.
     *
     * @param ReturnOrder $returnOrder The request object ready for serialization.
     * @param string $requestJson The actual message sent to the web service.
     */
    public static function assertLabelRequest(ReturnOrder $returnOrder, string $requestJson)
    {
        $expected = json_decode(json_encode($returnOrder), true);
        $actual = json_decode($requestJson, true);

        Assert::assertSame($expected['receiverId'], $actual['receiverId']);
        Assert::assertSame($expected['customerReference'], $actual['customerReference']);
        Assert::assertSame($expected['returnDocumentType'], $actual['returnDocumentType']);
        Assert::assertSame($expected['email'], $actual['email']);

        $expectedSender = $expected['senderAddress'];
        $actualSender = $actual['senderAddress'];

        Assert::assertSame($expectedSender['name1'], $actualSender['name1']);
        Assert::assertSame($expectedSender['streetName'], $actualSender['streetName']);
        Assert::assertSame($expectedSender['houseNumber'], $actualSender['houseNumber']);
        Assert::assertSame($expectedSender['postCode'], $actualSender['postCode']);
        Assert::assertSame($expectedSender['city'], $actualSender['city']);
        Assert::assertSame($expectedSender['country']['countryISOCode'], $actualSender['country']['countryISOCode']);

        if (isset($expected['customsDocument'])) {
            $expectedCustoms = $expected['customsDocument'];
            $actualCustoms = $actual['customsDocument'];

            Assert::assertSame($expectedCustoms['currency'], $actualCustoms['currency']);
            Assert::assertSame($expectedCustoms['originalShipmentNumber'], $actualCustoms['originalShipmentNumber']);
            Assert::assertSame($expectedCustoms['originalOperator'], $actualCustoms['originalOperator']);
            Assert::assertSame($expectedCustoms['positions'], $actualCustoms['positions']);
        }
    }

    /**
     * Assert that the library's public API response object was properly generated from the response body.
     *
     * @param ConfirmationInterface $result
     * @param string $responseJson
     */
    public static function assertLabelResponse(ConfirmationInterface $result, string $responseJson)
    {
        $responseData = json_decode($responseJson, true);

        Assert::assertNotEmpty($result->getShipmentNumber());
        Assert::assertNotEmpty($result->getRoutingCode());
        Assert::assertSame($responseData['shipmentNumber'], $result->getShipmentNumber());
        Assert::assertSame($responseData['routingCode'], $result->getRoutingCode());

        Assert::assertSame($responseData['labelData'] ?? '', $result->getLabelData());
        Assert::assertSame($responseData['qrLabelData'] ?? '', $result->getQrLabelData());
    }

    /**
     * Assert that logger contains an entry for client/server error (request level, HTTP status 400/500).
     *
     * @param TestLogger $logger Test logger.
     * @param string $responseBody Pre-recorded response.
     * @return void
     * @throws ExpectationFailedException
     */
    public static function assertErrorLogged(TestLogger $logger, string $responseBody = '')
    {
        Assert::assertTrue($logger->hasErrorRecords(), 'No error logged.');

        if ($responseBody) {
            $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';
            $hasResponseStatus = $logger->hasErrorThatMatches($statusRegex);
            Assert::assertTrue($hasResponseStatus, 'Logged messages do not contain response status code.');

            $hasResponse = $logger->hasErrorThatContains($responseBody);
            Assert::assertTrue($hasResponse, 'Error message not logged.');
        }
    }

    /**
     * Assert that logger contains records with HTTP status code and messages.
     *
     * @param TestLogger $logger Test logger.
     * @param string $requestBody Client's last request body.
     * @param string $responseJson Pre-recorded response.
     * @return void
     * @throws ExpectationFailedException
     */
    public static function assertCommunicationLogged(TestLogger $logger, string $requestBody, string $responseJson = '')
    {
        Assert::assertTrue($logger->hasInfoRecords(), 'Logger has no info messages');

        $hasRequest = $logger->hasInfoThatContains($requestBody) || $logger->hasErrorThatContains($requestBody);
        Assert::assertTrue($hasRequest, 'Logged messages do not contain request');

        if ($responseJson) {
            $statusRegex = '|^HTTP/\d\.\d\s\d{3}\s[\w\s]+$|m';
            $hasStatusCode = $logger->hasInfoThatMatches($statusRegex) || $logger->hasErrorThatMatches($statusRegex);
            Assert::assertTrue($hasStatusCode, 'Logged messages do not contain status code.');

            $hasResponse = $logger->hasInfoThatContains($responseJson) || $logger->hasErrorThatContains($responseJson);
            Assert::assertTrue($hasResponse, 'Logged messages do not contain response');
        }
    }
}
