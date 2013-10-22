# Joomla Framework Manual

## Folder Structure

The following outlines the purpose of the top-level folder structure of
the Joomla Framework as found in the [GitHub repository](https://github.com/joomla/joomla-framework/ "Joomla Framework Github repository").

Folder     | Description
---------- | --------------------
/build     | Contains information relevant for building code style reports about the platform. Output from various automated processes may also end up in this folder.
/docs      | Contain developer manuals in Markdown format.
/src       | Contains all the server-side PHP code used in the Joomla Framework API.
/tests     | Contains general unit tests used for quality control. (Package specific tests are located in their respective packages.)
/vendor    | Contains composer-installable code that is required by the Joomla Framework.

## Bootstrapping

In order to bootstrap the Joomla Framework, you are required to define
a single constant, `JPATH_ROOT`. This should point to the root of your
application structure, the directory that contains the `src` and `vendor`
directories.

## Class Auto-loading

All class auto-loading is handled by [Composer](http://getcomposer.org).
The Joomla Framework and it's packages follow the [PSR-0](http://www.php-fig.org/psr/psr-0/)
auto-loading standard. The Framework packages are located in the `src/` directory.
