#!/bin/bash
cd /var/www
php composer.phar self-update
php composer.phar install
cp build/vagrant/local.php config/autoload/local.orig.php
cp build/vagrant/local.php config/autoload/local.php
cp build/vagrant/test.php config/autoload/test.php
