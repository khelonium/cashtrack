#!/bin/sh
cd /var/www
cp -f build/codeception.vagrant.yml ./codeception.yml
cp -f build/acceptance.suite.vagrant.yml ./tests/acceptance.suite.yml
cp -f build/api.suite.vagrant.yml ./tests/api.suite.yml
cp config/autoload/test.php config/autoload/local.php
mkdir ./tests/_log
sudo chmod a+w ./tests/_log
php codecept.phar run
cp config/autoload/local.orig.php config/autoload/local.php
