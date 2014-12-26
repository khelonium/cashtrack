#!/bin/bash

cd tests
../vendor/bin/phpunit
cd system
./run.sh
