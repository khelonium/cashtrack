#!/bin/bash
./build/setup/setup_test_db.sh
cd tests
../vendor/bin/phpunit
cd system
./run.sh
