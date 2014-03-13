# Joomla Framework Manual

## Composer
The Joomla Framework package is a composer metapackage.  This means that there is no deployable code in the Framework package - it simply consists a list of each individual framework package.  When using composer these packages will be installed in the vendor/joomla directory.

## Package files
The Joomla Framework package does contain some files that can be used to perform unit testing on the entire framework at once.  It also contains some documentation that applies to all packages.

## Folder Structure

The following outlines the purpose of the top-level folder structure of
the Joomla Framework as found in the [GitHub repository](https://github.com/joomla/joomla-framework/ "Joomla Framework Github repository").

Folder     | Description
---------- | --------------------
/build     | Contains information relevant for building code style reports about the platform. Output from various automated processes may also end up in this folder.
/docs      | Contain developer manuals in Markdown format.
/tests     | Contains general unit tests used for quality control. (Package specific tests are located in their respective packages.)
/vendor    | Directory used by composer to install Joomla Framework packages and others as required

## Bootstrapping

In order to bootstrap the Joomla Framework, you are required to define
a single constant, `JPATH_ROOT`. This should point to the root of your
application structure, the directory that contains the `src` and `vendor`
directories.

## Class Auto-loading

All class auto-loading is handled by [Composer](http://getcomposer.org).
The Joomla Framework and it's packages follow the [PSR-0](http://www.php-fig.org/psr/psr-0/)
auto-loading standard. The individual Framework packages are located in their own seperate repositories and composer is used to install them.
