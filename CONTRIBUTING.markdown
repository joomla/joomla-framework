# Contributing to the Joomla! Framework

Pull requests are merged via Github, you can find the documentation about how to fork a repository and start
contributing to Joomla here [https://help.github.com/articles/fork-a-repo](https://help.github.com/articles/fork-a-repo).

All contributions are welcome to be submitted for review for inclusion in the Joomla Framework, but before they will be
accepted, we ask that you follow these simple steps:

* [Joomla Contributor Agreement](#joomla-contributor-agreement)
* [Coding standards](#coding-standards)
* [Unit Testing](#unit-testing)
* [Documentation](#documentation)

Please be patient as not all items will be tested or reviewed immediately by a Framework maintainer team.

Also be receptive to feedback about your additions or changes to the Framework. The maintainer team and other community
members may make suggestions or ask questions about your change. This is part of the review process, and helps everyone to understand what is happening, why it is happening, and potentially optimize your code.

If you need some ideas about what you can do, you will find tasks on the
[Issues](https://github.com/joomla/joomla-framework/issues) list. All tasks are allocated to a milestone, usually
representing a quarter of the calendar year. The milestones should be used as a guide to when various tasks should be
done, but there is generally no problem doing tasks out of order (providing there are no dependency problems).

All tasks are labelled into broad groups. For example, the
[backlog](https://github.com/joomla/joomla-framework/issues?labels=backlog&page=1&state=open) label shows all the tasks
that need to be competed to "catch up" on work that was missed out in previous versions of the Framework.

## Joomla Contributor Agreement

Ideally, everybody who contributes to the Joomla Framework, or any other Open Source Matters supported project for that
matter, should sign a [Joomla Contributor Agreement](http://developer.joomla.org/contributor-agreements.htm) (JCA).

But, we are aware that some contributors will not want to take the extra effort, especially for one-time contributors of
modest amounts of code.  As a compromise, the Joomla Project requires a JCA from anybody who makes a significant
contribution to Joomla or any other OSM project.  "Significant" is, of course, a judgment call.  As a general guideline,
if you as an individual have contributed or intend to contribute over 100 lines of code to the Joomla Framework, we need
a JCA.

If you are contributing as an employee of a company (that is, the work you are contributing was done on company time)
then we need a JCA with your company no matter how small the contribution is.

## Versioning

When you add new classes, properties or methods, use `__DEPLOY_VERSION__` in the `@since` tags in Docblocks.
We'll replace that special tag with the actual version the changes are deployed in.

## Coding standards

The submitted code must be compatible with the Joomla coding standard. You can read about the Joomla coding standards
here:

 * [Preface](docs/coding-standards)
 * [Basic Guidelines](docs/coding-standards)
 * [PHP](docs/php)
 * [Comments](docs/comments)

There is a tool called [PHP Code Sniffer](http://pear.php.net/package/PHP_CodeSniffer) that allows you to validate your
code against the Joomla coding standard.

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

Eclipse : [http://www.websitefactors.co.uk/php/2011/10/installing-php-codesniffer-properly-in-eclipse/]
(http://www.websitefactors.co.uk/php/2011/10/installing-php-codesniffer-properly-in-eclipse/)

PHPStorm : [http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html]
(http://www.jetbrains.com/phpstorm/webhelp/using-php-code-sniffer-tool.html)

Sublime Text : [http://www.soulbroken.co.uk/code/sublimephpcs/](http://www.soulbroken.co.uk/code/sublimephpcs/)

### Auto formatter

You can find configuration files for your editor formatter on this repository :
[https://github.com/joomla/coding-standards/tree/master/IDE](https://github.com/joomla/coding-standards/tree/master/IDE)

Download the repository content via the Zip button and import the appropriate `.xml` file into your editor.

## Unit Testing

Whether your pull request is a bug fix or introduces new classes or methods to the Framework, we ask that you include
unit tests for your changes.

We understand that not all users submitting pull requests will be proficient with PHPUnit. The maintainers and community
as a whole are a helpful group and can help you with writing tests.

The PHPUnit manual contains all the documentation you need in order to install it and begin to write unit tests :
[http://www.phpunit.de/manual/current/en/](http://www.phpunit.de/manual/current/en/).

## Documentation

Documentation for each package in the Joomla Framework can be found in the `README.me` file in the main package folder.
The file uses Github flavoured Markdown format. You can find out more about this format at
[http://daringfireball.net/projects/markdown/syntax](http://daringfireball.net/projects/markdown/syntax).

When contributing new features to existing packages, please add notes about the new features to the existing `README.md`
files in the packages you change.

When submitting new packages, documentation in the form of a `README.md` file will be required with your pull request.

The package documentation should explain how a developer should should be able to get started using the code in the
package. The documentation should explain an explanation of the classes and/or interfaces and provide several simple
examples.
