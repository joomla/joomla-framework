<?PHP
// A simple script to dump info on the php executable

phpinfo();
print_r(gd_info());
print_r(get_defined_constants(true));
print_r(get_loaded_extensions());
print_r(get_defined_functions());
print_r(get_declared_classes());
print_r(get_defined_vars());
