#!/bin/sh

MYCOMPOSER="$HOME/composer"
echo "---> Starting $(tput bold ; tput setaf 2)hack of composer$(tput sgr0)"

git clone git://github.com/garyamort/composer.git $MYCOMPOSER
cd $MYCOMPOSER
composer install

