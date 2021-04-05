#!/bin/bash

echo "================================"
echo " Tests"
echo "================================"
echo ""

echo ""
echo "----------------"
echo " Fresh start from a new DB"
echo "----------------"
APP_ENV=test ./bin/console d:d:d -n --if-exists --force
APP_ENV=test ./bin/console d:d:c
APP_ENV=test ./bin/console d:m:mi -n
APP_ENV=test ./bin/console d:f:l -n --group=test

echo ""
echo "----------------"
echo " Running Smoke tests on the Back Office"
echo "----------------"
APP_ENV=test ./bin/phpunit tests/Back/SmokeTest.php

echo ""
echo "----------------"
echo " Running Unit tests"
echo "----------------"
echo "TODO..."

echo ""
echo "----------------"
echo " Running Functionnal tests on our BackOffice Controllers"
echo "----------------"
APP_ENV=test ./bin/phpunit tests/Back/Controller/DeliveryPointController.php