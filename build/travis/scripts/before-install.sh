#!/bin/bash

echo "Executing for PHP version $TRAVIS_PHP_VERSION"

  if [ $TRAVIS_PHP_VERSION = "hhvm" ]
  then
    set -vx
    "$TRAVIS_BUILD_DIR/build/travis/scripts/apt-get-prehhvm.sh"
  else
    set -vx
  	"$TRAVIS_BUILD_DIR/build/travis/scripts/apt-get.sh"
  fi
