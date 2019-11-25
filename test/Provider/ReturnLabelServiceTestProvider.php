<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Test\Provider;

use Dhl\Sdk\Paket\Retoure\Exception\RequestValidatorException;

/**
 * Class ReturnLabelServiceTestProvider
 *
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class ReturnLabelServiceTestProvider
{
    /**
     * @return \JsonSerializable[][]|string[][]
     * @throws RequestValidatorException
     */
    public static function labelSuccess(): array
    {
        $standardRequest = ReturnLabelRequestProvider::validRequest();
        $pdfLabelRequest = ReturnLabelRequestProvider::validRequest('PDF');
        $qrLabelRequest = ReturnLabelRequestProvider::validRequest('QR');
        $nonEuRequest = ReturnLabelRequestProvider::validCustomsRequest();

        $standardResponse = ReturnLabelResponseProvider::successResponse();
        $pdfResponse = ReturnLabelResponseProvider::pdfResponse();
        $qrResponse = ReturnLabelResponseProvider::qrResponse();

        return [
            'domestic request with pdf and qr' => [$standardRequest, $standardResponse],
            'domestic request with pdf' => [$pdfLabelRequest, $pdfResponse],
            'domestic request with qr' => [$qrLabelRequest, $qrResponse],
            'non-eu request' => [$nonEuRequest, $pdfResponse],
        ];
    }

    /**
     * @return \JsonSerializable[][]|int[][]|string[][]
     * @throws RequestValidatorException
     */
    public static function labelError(): array
    {
        $validRequest = ReturnLabelRequestProvider::validRequest();

        // credentials issues
        $unauthorizedResponse = ReturnLabelResponseProvider::unauthorized(); // basic auth error
        $authFailedResponse = ReturnLabelResponseProvider::authenticationFailed(); // auth token error

        // request data issues
        $badRequest = ReturnLabelRequestProvider::validationErrorRequest(); // invalid required value
        $validationErrorResponse = ReturnLabelResponseProvider::validationFailed();

        // service uri issues
        $forbiddenResponse = ReturnLabelResponseProvider::forbidden(); // wrong service path
        $serverErrorHtmlResponse = ReturnLabelResponseProvider::serverErrorHtml(); // missing trailing slash

        return [
            '401 Unauthorized (basic auth)' => [$validRequest, 401, 'text/html', $unauthorizedResponse],
            '400 Bad Request (DPDHL header)' => [$validRequest, 400, 'application/json', $authFailedResponse],
            '400 Bad Request (message validation)' => [$badRequest, 400, 'application/json', $validationErrorResponse],
            '403 Forbidden (wrong service path)' => [$validRequest, 403, 'text/html', $forbiddenResponse],
            '500 Internal Server Error (HTML)' => [$validRequest, 500, 'text/html', $serverErrorHtmlResponse]
        ];
    }

    /**
     * @return \JsonSerializable[][]
     * @throws RequestValidatorException
     */
    public static function networkError(): array
    {
        return [
            'network error' => [ReturnLabelRequestProvider::validRequest()],
        ];
    }
}
