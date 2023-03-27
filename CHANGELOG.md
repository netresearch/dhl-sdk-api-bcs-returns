# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 2.1.2

### Fixed

- Prevent application crash when API returns empty response, reported via issue [#9](https://github.com/netresearch/dhl-sdk-api-bcs-returns/issues/9).

## 2.1.1

### Fixed

- Handle missing `qrLabelData` field in web service response, reported via issue [#6](https://github.com/netresearch/dhl-sdk-api-bcs-returns/issues/6)

## 2.1.0

### Added

- Support for PHP 8

### Removed

- Support for PHP 7.1

## 2.0.1

### Changed

- Rename method argument to align with web service field name.

## 2.0.0

### Changed

- HTTPlug package is upgraded to version 2.
- PHP-HTTP packages are replaced by their PSR successors. SDK now requires a `psr/http-client-implementation`.

### Removed

- PHP 7.0 is no longer supported.

## 1.0.0

- Initial release
