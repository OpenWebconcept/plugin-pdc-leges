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

# Remove current vendor folder (if any)
# and install the dependencies without dev packages.
cd ./releases/pdc-leges || exit
composer install -o --no-dev

# Remove unneeded files in a WordPress plugin
rm -rf ./.git ./composer.json ./.gitignore ./.editorconfig ./.eslintignore \
	./.eslintrc ./.php-cs-fixer.php ./composer.lock ./bin \
	./phpstan.neon.dist ./phpunit.xml.dist ./tests \
	./DOCKER_ENV ./docker_tag ./output.log ./.github

cd ../

# Create a zip file from the optimized plugin folder
zip -rq pdc-leges.zip ./pdc-leges
rm -rf ./pdc-leges

echo "Zip completed @ $(pwd)/pdc-leges.zip"
