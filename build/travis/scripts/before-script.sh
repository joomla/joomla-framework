#!/bin/bash

echo "Executing for PHP version $TRAVIS_PHP_VERSION"

  if [ $TRAVIS_PHP_VERSION == "hhvm" ]
  then
    echo "hhvm does not have any before script steps"
  else
    echo "php 5.x does not have any before script steps"
  fi


