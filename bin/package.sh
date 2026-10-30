#!/bin/bash

## Remove old packages
rm -rf ./releases
mkdir -p ./releases

# Copy current dir to tmp
rsync \
	-ua \
	--exclude='vendor/*' \
	--exclude='releases/*' \
	./ ./releases/pdc-leges/

cd ./releases/pdc-leges || exit

# The release zip ships its own vendor folder and must run on the lowest
# supported PHP version (7.4). composer.json deliberately does not pin
# dependencies (e.g. symfony/finder) to PHP 7.4 compatible versions, because
# that would force those versions on PHP 8 projects that install this plugin
# via Composer. Instead, resolve the production dependencies for PHP 7.4 here.
# require-dev is removed first: its packages (e.g. phpunit) need PHP 8.1+ and
# would otherwise make resolution fail, even with --no-dev.
set -e
composer config --unset require-dev
composer config platform.php 7.4.33
composer update -o --no-dev
set +e

# Remove unneeded files in a WordPress plugin
rm -rf ./.git ./composer.json ./.gitignore ./.editorconfig ./.eslintignore \
	./.eslintrc ./.php-cs-fixer.php ./composer.lock ./bin \
	./phpstan.neon.dist ./phpunit.xml.dist ./phpunit.xml ./phpcs.xml.dist \
	./package.json ./package-lock.json ./bitbucket-pipelines.yml ./tests \
	./DOCKER_ENV ./docker_tag ./output.log ./.github

cd ../

# Create a zip file from the optimized plugin folder
zip -rq pdc-leges.zip ./pdc-leges
rm -rf ./pdc-leges

echo "Zip completed @ $(pwd)/pdc-leges.zip"
