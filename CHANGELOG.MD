# Change Log
All notable changes to this project will be documented in this file.

## [1.0.13] 2019.11.01
### Added
- Fields for billing address in order - PSD/2 regulation

### Changed
- Order of parameters for the customer constructor
- Add method for add delivery address when available
- Make billing address as mandatory - thrown MissingBillingAddressException when empty

### Fixed
- Refund test with refund product

## [1.0.12] 2019.02.20
### Added
- This changelog file

### Fixed
- Problem with unsupported characters

## [1.0.11] 2018.09.14
### Added
- Possibility to send refund product
- Possibility to generate RSA keys

## [1.0.10] 2018.09.03
### Changed
- Error message for response

## [1.0.9] 2018.08.28
### Fixed
- Problem with parse response when skip confirmation page is set to false

## [1.0.8] 2018.07.16
### Fixed
- Problem with generate request id
