#!/bin/bash

# Install dependencies
composer install --no-dev

# Recreate demo database
AUTORUN_SCRIPT=true php ./scripts/db/drop.php
AUTORUN_SCRIPT=true php ./scripts/db/create.php
AUTORUN_SCRIPT=true php ./scripts/db/seed.php

# Delete unused files
rm -rf ./scripts
rm -rf ./tests
rm -rf ./docs

# Deploy current git version to VERSION.txt
git log -1 --format="Version: %h <> Last update: %ad" --date=short > ./VERSION.txt