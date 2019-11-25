<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Test\Provider;

/**
 * Class ReturnLabelResponseProvider
 *
 * @author  Andreas Müller <andreas.mueller@netresearch.de>
 * @link    https://netresearch.de
 */
class ReturnLabelResponseProvider
{
    /**
     * @return string
     */
    public static function successResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/labelResponse.json') ?: '';
    }

    /**
     * @return string
     */
    public static function pdfResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/labelResponsePdf.json') ?: '';
    }

    /**
     * @return string
     */
    public static function qrResponse(): string
    {
        return \file_get_contents(__DIR__ . '/_files/labelResponseQr.json') ?: '';
    }

    /**
     * @return string
     */
    public static function unauthorized(): string
    {
        return \file_get_contents(__DIR__ . '/_files/unauthorized.html') ?: '';
    }

    /**
     * @return string
     */
    public static function authenticationFailed(): string
    {
        return \file_get_contents(__DIR__ . '/_files/authenticationFailed.json') ?: '';
    }

    /**
     * @return string
     */
    public static function forbidden(): string
    {
        return \file_get_contents(__DIR__ . '/_files/forbidden.html') ?: '';
    }

    /**
     * @return string
     */
    public static function validationFailed(): string
    {
        return \file_get_contents(__DIR__ . '/_files/validationFailed.json') ?: '';
    }

    /**
     * @return string
     */
    public static function serverError(): string
    {
        return \file_get_contents(__DIR__ . '/_files/serverError.json') ?: '';
    }

    /**
     * @return string
     */
    public static function serverErrorHtml(): string
    {
        return \file_get_contents(__DIR__ . '/_files/serverError.html') ?: '';
    }
}
