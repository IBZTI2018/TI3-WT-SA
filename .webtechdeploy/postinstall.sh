#!/bin/bash

# Install dependencies
composer install --no-dev

# Recreate demo database
php ./scripts/db/drop.php
php ./scripts/db/create.php
php ./scripts/db/seed.php

# Delete unused files
rm -rf ./scripts
rm -rf ./tests
rm -rf ./docs
