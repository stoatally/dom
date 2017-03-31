#!/bin/bash
set -e
set -o pipefail

composer self-update
composer install --dev --prefer-source