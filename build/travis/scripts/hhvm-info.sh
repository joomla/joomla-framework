#!/bin/sh

PHPINFO = "<?PHP phpinfo(); ?>"
PHPIFILE = "$HOME/phpinfo.php"

rm -f $PHPIFILE
echo $PHPINFO > $PHPIFILE
