#!/bin/sh


echo "---> Starting $(tput bold ; tput setaf 2)list system info$(tput sgr0)"

uname -a
lsb_release -a
dpkg -l

