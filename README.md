# DHL Paket Retoure API SDK

The DHL Paket Retoure API SDK package offers an interface to the following web services:

- API Retoure

## Requirements

### System Requirements

- PHP 7.1+ with JSON extension

### Package Requirements

- `php-http/discovery`: Discovery service for HTTP client and message factory implementations
- `php-http/httplug`: Pluggable HTTP client abstraction
- `php-http/logger-plugin`: HTTP client logger plugin for HTTPlug
- `psr/http-client`: PSR-18 HTTP client interfaces
- `psr/http-factory`: PSR-7 HTTP message factory interfaces
- `psr/http-message`: PSR-7 HTTP message interfaces
- `psr/log`: PSR-3 logger interfaces

### Virtual Package Requirements

- `psr/http-client-implementation`: Any package that provides a PSR-18 compatible HTTP client
- `psr/http-factory-implementation`: Any package that provides PSR-7 compatible HTTP message factories
- `psr/http-message-implementation`: Any package that provides PSR-7 HTTP messages

### Development Package Requirements

- `nyholm/psr7`: PSR-7 HTTP message factory & message implementation
- `phpunit/phpunit`: Testing framework
- `php-http/mock-client`: HTTPlug mock client implementation
- `phpstan/phpstan`: Static analysis tool
- `squizlabs/php_codesniffer`: Static analysis tool

## Installation

```bash
$ composer require dhl/sdk-api-bcs-returns
```

## Uninstallation

```bash
$ composer remove dhl/sdk-api-bcs-returns
```

## Testing

```bash
$ ./vendor/bin/phpunit -c test/phpunit.xml
```

## Features

The DHL Paket Retoure API SDK supports the following features:

* Book return labels ([`BookLabel`](https://entwickler.dhl.de/group/ep/wsapis/retouren))

### Return Label Service

Create a return label PDF or QR code to be scanned by a place of committal (e.g. post office).
For return shipments from outside of the EU, a customs document can also be requested.

#### Public API

The library's components suitable for consumption comprise of

* service:
  * service factory
  * return label service
  * data transfer object builder
* data transfer objects:
  * authentication storage
  * booking confirmation with label data

#### Usage

```php
$authStorage = new \Dhl\Sdk\Paket\Retoure\Auth\AuthenticationStorage(
    'applicationId',
    'applicationToken',
    'user',
    'signature'
);
$logger = new \Psr\Log\NullLogger();

$serviceFactory = new \Dhl\Sdk\Paket\Retoure\Service\ServiceFactory();
$service = $serviceFactory->createReturnLabelService($authStorage, $logger, $sandbox = true);

$requestBuilder = new \Dhl\Sdk\Paket\Retoure\Model\ReturnLabelRequestBuilder();
$requestBuilder->setAccountDetails($receiverId = 'DE');
$requestBuilder->setShipperAddress(
    $name = 'Jane Doe',
    $countryCode = 'DE',
    $postalCode = '53113',
    $city = 'Bonn',
    $streetName = 'Sträßchensweg',
    $streetNumber = '2'
);

$returnOrder = $requestBuilder->create();
$confirmation = $service->bookLabel($returnOrder);
```
