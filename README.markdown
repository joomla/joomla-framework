# Joomla Framework

[![Build Status](https://travis-ci.org/joomla/joomla-framework.png?branch=master)](https://travis-ci.org/joomla/joomla-framework)

## What is the Joomla Framework ?

The `Joomla Framework` is a platform for writing Web and command line applications in PHP.  It is free and open source software,
distributed under the GNU General Public License version 2 or later.

The Joomla Content Management System (CMS) is built on top of the `Joomla Framework`.
For more information about the Joomla CMS visit: http://www.joomla.org/about-joomla.html

You can find out more about Joomla development at: http://docs.joomla.org/Developers.

You can discuss Joomla Framework development at: http://groups.google.com/group/joomla-dev-platform.

## Requirements

* PHP 5.3.10

## Installation

### Via Composer

- Create a project and add a `composer.json` file to it with the content :

```json
{
    "require": {
        "joomla/joomla-framework": "dev-master"
    }
}
```

- Download Composer

`curl -sS https://getcomposer.org/installer | php`

- Install the Joomla Framework

`php composer.phar install`

### Via Git

`git clone git://github.com/joomla/joomla-framework.git`

## Documentation

The documentation can be found [here](docs/).

## Contributing

All kind of contributions are welcome,
please consult the document about how to contribute [here](CONTRIBUTING.markdown).

## Running Unit Tests

`phpunit`
