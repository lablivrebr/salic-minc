#!/bin/sh

../../vendor/bin/phpunit --debug --colors --verbose -c  ../phpunit.xml --no-coverage --testsuite Assinatura
#docker run --rm --name=php-cli -v $(pwd)/../..:/app phpunit/phpunit:latest php ./vendor/bin/phpunit --debug --colors --verbose -c  ./tests/phpunit.xml --no-coverage --testsuite Assinatura
