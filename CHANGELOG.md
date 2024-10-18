# CHANGELOG

## 2.2.4

### Fixed

- Add prefix to deactivation function to avoid conflict.

## 2.2.3

### Fixed

- Possible $page array was allowed without an ID during registration of settings pages.

## 2.2.2

### Changed

- Remove ',-' from old lege prices while executing WP Cron event LegesPricesSaveFormat

## 2.2.1

### Fixed

- number_format_i18n failed when price param was not float

## 2.2.0

### Added

- A new WP Cron event that converts the meta values '_pdc-lege-price' and '_pdc-lege-new-price' from string format (e.g., "196,00") to float string format (e.g., "196.00"). This change ensures consistent data storage and improves compatibility with arithmetic operations and data processing. The event is executed only once upon installation of this version.
- New meta fields added to the Custom Post Type (CPT) `pdc-leges` to support the enhanced functionality and data structure.

## 2.1.0

### Changed

- Replaced the Metabox.io plugin with the open-source CMB2 plugin to align with OpenWebConcept's policy of using open-source solutions. This change is fully backward-compatible, except for cases where an external plugin or theme uses the 'rwmb_meta_boxes' hook to add custom metaboxes.
- Updated translations
- License

## 2.0.0

### Added

- Register REST endpoints
- Replaced commands with WP Cron Events, reducing the need for server cron job configuration
- Updated `README.md`

## 1.2.5

### Changed

- No changes. Corrects version and git tag which were out of sync

## 1.2.4

### Changed

- No changes. Corrects version and git tag which were out of sync

## 1.2.3

### Fixed

- Avoid exiting with an error code when there is nothing to update

## 1.2.2

### Added

- Added search box to find leges items by ID

## 1.2.1

### Fixed

- Missing PHP extension when running GH workflows

## 1.2.0

### Added

- Updates can now be provided through the Admin interface

## 1.1.4

### Changed

- Replaced Composer plugin dependency check with runtime check

## 1.1.3

### Fixed

- Validate `datActive` in `dateIsNow` inside Shortcode class

## 1.1.2

### Changed

- Update dependencies and reference pdc-base plugin from BitBucket to GitHub

## 1.1.1

### Fixed

- Fix new lege price date comparison

## 1.1.0

### Added

- Update lege prices via WP_CLI command

## 1.0.4

### Changed

- Composer dependency on pdc-base plug-in updated from `^2.0.0` to `^3.0.0`

## 1.0.3

### Fixed

- WordPress 5.3 class and style changes caused issues with quickedit functionality

## 1.0.2

### Added

- Adding of admin column

## 1.0.1

### Fixed

- Check if required file for `is_plugin_active` is already loaded, otherwise load it. Props @Jasper Heidebrink

## 1.0.0

### Added

- Initial release
- Added documentation
