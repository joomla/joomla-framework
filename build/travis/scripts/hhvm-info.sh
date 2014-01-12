#!/bin/sh

PHPINFO = "<?PHP phpinfo(); ?> "
PHPEXT = "<?PHP print_r(get_loaded_extensions()); ?> "
PHPF = "<?PHP print_r(get_defined_functions()); ?> "
PHPC = "<?PHP print_r(get_declared_classes()); ?> "
PHPV = "<?PHP print_r(get_defined_vars()); ?> "
PHPIFILE = "$HOME/phpinfo.php"

rm -f $PHPIFILE
echo $PHPINFO >> $PHPIFILE
echo $PHPEXT >> $PHPIFILE
echo $PHPF >> $PHPIFILE
echo $PHPC >> $PHPIFILE
echo $PHPV >> $PHPIFILE

