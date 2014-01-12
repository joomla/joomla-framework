#!/bin/sh

PHPINFO = "<?PHP echo phpinfo(); ?>"
PHPIFILE = "$HOME/phpinfo.php"

rm -f $PHPIFILE
echo $PHPINFO > $PHPIFILE
