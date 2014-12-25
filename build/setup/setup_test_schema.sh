#!/bin/bash

echo "Droping and creating the database";
mysql -u root -p123 -e "DROP DATABASE yourcashflowtest;CREATE DATABASE yourcashflowtest";
echo "Recreating schema";
mysqldump -u root -p123 --no-data cashtrack | mysql -u root -p123  yourcashflowtest

