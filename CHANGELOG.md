# Changelog

## [NEXT] - 2026-11-02

-   Fix: release workflow builds the zip on PHP 7.4 and resolves its dependencies to PHP 7.4 compatible versions
-   Chore: add pdc-base as dev dependency and migrate phpunit config
-   Change: exclude more development files from the release zip
-   Added: `Requires PHP: 7.4` plugin header

## [v2.4.1] - 2026-10-30

-   Fix: allow PDC lege meta changes in quick edit

## [v2.4.0] - 2026-09-04

-   Added: allow the shortcode id attribute to take a comma separated list of leges and render the total price
-   Added: document the shortcode and its id attribute in the README
-   Fix: stop silently truncating a comma separated id attribute to its first lege
-   Fix: fatal error on the plugins screen when a required plugin is missing: the dependency notice was invoked instead of registered as a callback

## [v2.3.0] - 2026-04-24

-   Added: percentage field to Leges post type with toggle option for using percentage instead of price
-   Added: percentage validation and sanitization
-   Added: percentage translations and update text domain loading
-   Added: use percentage toggle to shortcode logic
-   Fix: mock wp_unslash function in QuickEditServiceProviderTest
-   Change: format PHP code
-   Change: update composer.lock

## [v2.2.10] - 2026-03-17

-   Fix: cleanup dependencies

## [v2.2.9] - 2025-03-25

-   Fix: autoloader

## [v2.2.8] - 2025-03-13

-   Fix: make johnpbloch/wordpress a dev dependency

## [v2.2.7] - 2025-03-13

-   Fix: text domain

## [v2.2.6] - 2025-01-21

-   Fix: apply reversed order of args in apply_filters.

## [v2.2.5] - 2025-01-20

-   Fix: normalize price formats and handle '0.00' and '0,00' correctly in update lege prices cron event.

## [v2.2.4] - 2024-10-18

-   Fix: add prefix to deactivation function to avoid conflict.

## [v2.2.3] - 2024-10-09

-   Fix: possible $page array was allowed without an ID during registration of settings pages.

## [v2.2.2] - 2024-09-24

-   Change: remove ',-' from old lege prices while executing WP Cron event LegesPricesSaveFormat

## [v2.2.1] - 2024-09-21

-   Fix: number_format_i18n failed when price param was not float

## [v2.2.0] - 2024-09-09

-   Added: a new WP Cron event that converts the meta values '\_pdc-lege-price' and '\_pdc-lege-new-price' from string format (e.g., "196,00") to float string format (e.g., "196.00"). This change ensures consistent data storage and improves compatibility with arithmetic operations and data processing. The event is executed only once upon installation of this version.
-   Added: new meta fields added to the Custom Post Type (CPT) `pdc-leges` to support the enhanced functionality and data structure.

## [v2.1.0] - 2024-07-17

-   Change: replaced the Metabox.io plugin with the open-source CMB2 plugin to align with OpenWebConcept's policy of using open-source solutions. This change is fully backward-compatible, except for cases where an external plugin or theme uses the 'rwmb_meta_boxes' hook to add custom metaboxes.
-   Change: updated translations
-   Change: license

## [v2.0.0] - 2024-07-15

-   Added: register REST endpoints
-   Added: replaced commands with WP Cron Events, reducing the need for server cron job configuration
-   Added: updated `README.md`

## [v1.2.5] - 2024-07-01

-   Change: no changes. Corrects version and git tag which were out of sync

## [v1.2.4] - 2024-06-28

-   Change: no changes. Corrects version and git tag which were out of sync

## [v1.2.3] - 2023-12-13

-   Fix: avoid exiting with an error code when there is nothing to update

## [v1.2.2] - 2023-12-13

-   Added: search box to find leges items by ID

## [v1.2.1] - 2023-06-28

-   Fix: missing PHP extension when running GH workflows

## [v1.2.0] - 2023-06-28

-   Added: updates can now be provided through the Admin interface

## [v1.1.4] - 2023-06-26

-   Change: replaced Composer plugin dependency check with runtime check

## [v1.1.3] - 2022-12-21

-   Fix: validate `datActive` in `dateIsNow` inside Shortcode class

## [v1.1.2] - 2022-10-07

-   Change: update dependencies and reference pdc-base plugin from BitBucket to GitHub

## [v1.1.1] - 2022-05-04

-   Fix: new lege price date comparison

## [v1.1.0] - 2022-05-04

-   Added: update lege prices via WP_CLI command

## [v1.0.4] - 2020-08-04

-   Change: Composer dependency on pdc-base plug-in updated from `^2.0.0` to `^3.0.0`

## [v1.0.3] - 2020-01-17

-   Fix: WordPress 5.3 class and style changes caused issues with quickedit functionality

## [v1.0.2] - 2019-12-13

-   Added: admin column

## [v1.0.1] - 2019-05-09

-   Fix: check if required file for `is_plugin_active` is already loaded, otherwise load it. Props @Jasper Heidebrink

## [v1.0.0] - 2018-10-03

-   Added: initial release
-   Added: documentation
