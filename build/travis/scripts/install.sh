#!/bin/sh

echo "Executing for PHP version $TRAVIS_PHP_VERSION"

  if [ $TRAVIS_PHP_VERSION == "hhvm" ]
  then
    set -vx
    "$TRAVIS_BUILD_DIR/build/travis/scripts/apt-get-hhvm.sh"
  else
  	echo "Normal PHP does not require install phase"
  fi

