# Contributing to the Joomla! Framework

Pull requests are merged via Github, you can find the documentation about how to fork a repository and start
contributing to Joomla here https://help.github.com/articles/fork-a-repo.

All contributions are welcome to be submitted for review for inclusion in the Joomla! Framework,
but before they will be accepted, we ask that you follow these simple steps:

* [Coding standards](https://github.com/joomla/joomla-framework/blob/master/CONTRIBUTING.markdown#coding-standards)
* [Unit Testing](https://github.com/joomla/joomla-framework/blob/master/CONTRIBUTING.markdown#unit-testing)
* [Documentation](https://github.com/joomla/joomla-framework/blob/master/CONTRIBUTING.markdown#documentation)

## Coding standards

The submitted code must be compatible with the Joomla coding standard.

There is a useful tool called [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer) allowing you
to validate your code against the Joomla Framework standard.

### Installing PHP Code Sniffer

It must be installed via [PEAR](http://pear.php.net/), open a terminal and type the command :

``pear install PHP_CodeSniffer``

Once PHP Code Sniffer is installed you need to install the Joomla Standard.

### Installing the Joomla coding standard

``git clone http://github.com/joomla/coding-standards.git `pear config-get php_dir`/PHP/CodeSniffer/Standards/Joomla``

### Using PHP Code Sniffer

Now the Joomla coding Standard is installed, you can start validating your code style.

`phpcs --standard=Joomla path/to/code/`

### Visual Validation

Some editors support PHP Code Sniffer as a plugin or a built in feature.
It will allow you to see if your code matches the Joomla standard directly in your editor.

Eclipse : http://www.websitefactors.co.uk/php/2011/10/installing-php-codesniffer-properly-in-eclipse/

PHPStorm : http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html

### Auto formatter

You can find configuration files for your editor formatter on this repository :
https://github.com/joomla/coding-standards/tree/master/IDE

Download the repository content via the Zip button and import the appropriate .xml file into your editor.

## Unit Testing

Whether your pull request is a bug fix or introduces new classes or methods to the Framework
, we ask that you include unit tests for your changes.

We understand that not all users submitting pull requests will be proficient with PHPUnit.
The maintainers and community as a whole are a helpful group and can help you with writing tests.

The PHPUnit manual contains all the documentation you need in order to install it and begin to write unit tests :
http://www.phpunit.de/manual/current/en/index.html.

## Documentation

Feel free to expand on the existing documentation by adding to existing chapters or submitting new chapters.

When submitting new packages, documentation will be required with your pull request.

The Platform Manual is contained in the `docs` directory of this repo and is written in Markdown format.

You can find the documentation about this format here http://daringfireball.net/projects/markdown/syntax.

Please be patient as not all items will be tested or reviewed immediately by a Platform maintainer.

Lastly, please be receptive to feedback about your change.
The maintainers and other community members may make suggestions or ask questions about your change.
This is part of the review process, and helps everyone to understand what is happening,
why it is happening, and potentially optimize your code.
