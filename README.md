# OWC PDC Leges

## Getting started
### Installation

#### For users
1. Download the latest release from [the releases page](https://github.com/OpenWebconcept/plugin-pdc-leges/releases)
2. Unzip and move all files to the `/wp-content/plugins/plugin-pdc-leges` directory.
3. Log into the WordPress admin and activate the 'PDC Leges' plugin through the 'plugins' menu

#### For developers
To contribute to this project, no dependencies are required. However, you will need to download [Composer](https://getcomposer.org/) to run tests or create an optimized build of the plugin.

1. Clone this repository to your machine and/or WordPress installation
2. Optionally use Composer (`composer install`) to install the dev dependencies

To create an optimized and zipped build, run the `composer run package` command. This requires `Composer`, `rsync` and `zip` to run.


### Command
Lege prices can be updated automatically via a WP_CLI command, this command should be registered in a cron-job.
The full command name to use is 'wp owc-update-leges'.

##### Command 'wp owc-update-leges'
A lege has 3 settings:
- Current lege price
- New lege price
- Date active new lege price

The command queries all the leges which have valid values in the 'New lege price' and 'Date active new lege price' setting fields.
If the date in the 'Date active new lege price' settings field has been expired the 'Current lege price' settings field will be updated with the new price. If the update succeeds the 'New lege price' and 'Date active new lege price' setting fields will be cleared.

### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

##### Action for changing main Plugin object.
```php
'owc/pdc-leges/plugin'
```

Via the plugin object the following config settings can be adjusted
- metaboxes
- rest_api_fields


##### Filter the format of the shortcode output
```php
owc/pdc/leges/shortcode/format
```

##### Filter the output of the shortcode output
```php
owc/pdc/leges/shortcode/after-format
```

### Translations

If you want to use your own set of labels/names/descriptions and so on you can do so.
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/pdc-leges/languages/pdc-leges.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the 'Loco Translate' plugin.
https://wordpress.org/plugins/loco-translate/

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advise you to read the Beginner's guide page on their website: https://localise.biz/wordpress/plugin/beginners
or start at the homepage: https://localise.biz/wordpress/plugin

### Running tests
To run the Unit tests go to a command-line.
```bash
cd /path/to/wordpress/htdocs/wp-content/plugins/pdc-leges/
composer install
phpunit
```

For code coverage report, generate report with command line command and view results with browser.
```bash
phpunit --coverage-html ./tests/coverage
```

### Contribution guidelines

##### Writing tests
Have a look at the code coverage reports to see where more coverage can be obtained.
Write tests
Create a Pull request to the OWC repository

### Who do I talk to?

If you have questions about or suggestions for this plugin, please contact <a href="mailto:hpeters@Buren.nl">Holger Peters</a> from Gemeente Buren.
