#!/bin/sh

MYCOMPOSER="$HOME/composer"
echo "---> Starting $(tput bold ; tput setaf 2)hack of composer$(tput sgr0)"

git clone git://github.com/garyamort/composer.git $MYCOMPOSER
cd $MYCOMPOSER
composer install
echo "directory"
ls
echo "bin directory"
ls ./bin
echo "cat composer"
cat ./bin/composer
echo "my pwd"
pwd
MYCOMPOSEREXEC="$HOME/composer/bin/composer"
echo "---> Creating composer for $(tput bold ; tput setaf 2)$MYCOMPOSEREXEC$(tput sgr0)"

echo "Version of local composer"
$MYCOMPOSEREXEC$ --version
alias composer="$MYCOMPOSEREXEC$"
echo "Version of composer"
composer --version

