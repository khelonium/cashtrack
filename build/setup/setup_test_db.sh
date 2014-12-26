#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
$DIR/setup_test_schema.sh
$DIR/test_data.sh
