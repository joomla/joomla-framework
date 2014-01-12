#!/bin/sh

MYCOMPOSEREXEC="$HOME/composer/bin/composer"

echo "---> Creating composer for $(tput bold ; tput setaf 2)$MYCOMPOSEREXEC$(tput sgr0)"
echo "Version of local composer"
$MYCOMPOSEREXEC --version
alias composer="$MYCOMPOSEREXEC"
echo "Version of composer"
composer --version

