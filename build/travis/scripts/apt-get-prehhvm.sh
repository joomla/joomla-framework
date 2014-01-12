#!/bin/sh

echo $FOO

HHVM_PACKETS="php5-mcrypt php5-imagick php5-mysql"
if [ "$1" ]
then
    HHVM_PACKETS="$EXTRA_PACKETS $1"
fi

echo "---> Starting $(tput bold ; tput setaf 2)packets installation$(tput sgr0)"
echo "---> Packets to install : $(tput bold ; tput setaf 3)$HHVM_PACKETS$(tput sgr0)"

sudo apt-get update
sudo apt-get install -y --force-yes $HHVM_PACKETS
