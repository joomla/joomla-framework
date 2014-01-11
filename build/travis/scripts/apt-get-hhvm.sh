#!/bin/sh

echo $FOO

HHVM_PACKETS="hhvm-nightly"
if [ "$1" ]
then
    HHVM_PACKETS="$EXTRA_PACKETS $1"
fi

echo "---> Starting $(tput bold ; tput setaf 2)packets installation$(tput sgr0)"
echo "---> Packets to install : $(tput bold ; tput setaf 3)$HHVM_PACKETS$(tput sgr0)"

sudo apt-get update
APTCMD="sudo apt-get install -y --force-yes hhvm-nightly"
echo $APTCMD
$APTCMD
