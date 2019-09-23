<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Retoure\Serializer;

/**
 * JsonSerializer
 *
 * Serializer for outgoing request types and incoming responses.
 *
 * @package Dhl\Sdk\Paket\Retoure\Serializer
 * @author  Max Melzer <max.melzer@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class JsonSerializer
{
    /**
     * @param \JsonSerializable $request
     * @return string
     */
    public function encode(\JsonSerializable $request): string
    {
        // remove empty entries from serialized data (after all objects were converted to array)
        $payload = (string) \json_encode($request);
        $payload = (array) \json_decode($payload, true);
        $payload = $this->filterRecursive($payload);

        return (string) \json_encode($payload);
    }

    /**
     * Recursively filter null and empty strings from the given (nested) array
     *
     * @param array $element
     * @return array
     */
    private function filterRecursive(array $element): array
    {
        // Filter null and empty strings
        $filterFunction = static function ($entry): bool {
            return ($entry !== null) && ($entry !== '');
        };

        foreach ($element as &$value) {
            if (\is_array($value)) {
                $value = $this->filterRecursive($value);
            }
        }

        return array_filter($element, $filterFunction);
    }

    /**
     * @param string $jsonResponse
     * @return mixed
     */
    public function decode(string $jsonResponse)
    {
        $response = \json_decode($jsonResponse);

        return $response;
    }
}
