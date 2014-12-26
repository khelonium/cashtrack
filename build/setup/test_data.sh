#!/bin/bash

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
echo "Importing test data "
mysql -u root -p123 yourcashflowtest  < $DIR/account.sql
mysql -u root -p123 yourcashflowtest  < $DIR/category.sql


echo "Done";
