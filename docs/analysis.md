### Configuring Code Analysis Tools

#### Running Unit Tests

Before code gets pulled into the master repository, the unit testing
suite is run to ensure that the change candidates do not leave trunk in
an unstable state (i.e. all tests should pass at all times). In order to
make the process of getting your code accepted it is helpful to run
these tests locally to prevent any unexpected surprises.

The Joomla Framework unit tests are developed for use with PHPUnit 3.7,
which is the latest stable version as of March 2013. Please see the
[PHPUnit Manual](http://www.phpunit.de/manual/3.7/en/installation.html)
for information on installing PHPUnit on your system.

There are 2 ways to run the unit tests:
- They can be run from the root of the framework directory as a whole.
- They can be run from the root of each package individually to test just that package.

##### Configuring Your Environment: The Database

Standard unit tests run against a
[Sqlite](http://www.sqlite.org/quickstart.html) in memory database for
ease of setup and performance. Other than [installing
Sqlite](http://www.sqlite.org/quickstart.html) no manual intervention or
set up is required. The database is built at runtime and deleted when
finished.

To run the specific database tests:

-   Create your database and use the appropriate database-specific DDL
    located in src/Joomla/Database/Tests/Stubs to create the database tables
    required.

-   In the root directory, copy the file named phpunit.xml.dist, leaving
    it in the same folder and naming it phpunit.xml.

-   Uncomment the php block and include the const line(s) related to the
    database(s) you will be testing.

-   Set up the database configuration values for your specific
    environment.

##### Configuring Your Environment: The Joomla\Http\Transport Test Stubs

There is a special stub that is required for testing the Joomla\Http
transports so that actual web requests can be simulated and assertions
can be made about the results. To set these up, you'll need to do the
following:

-   In the root directory, copy the file named phpunit.xml.dist, leaving
    it in the same folder and naming it phpunit.xml.

-   Uncomment the php block and include the "JTEST\_HTTP\_STUB" const.

-   The default file path for the const assumes that you have checked
    out the Joomla Framework to the web root of your test environment
    inside a folder named "joomla-framework". If this is not the case,
    you can change the path to suit your environment and, if need be,
    copy the file from its default location to be available within your
    web environment.

##### Running the Tests

You can run the tests by going to the framework root directory and
executing `phpunit`

Alternatively, if you have Ant installed on your system, you may run the
unit tests by going to the framework root directory and executing
`ant phpunit` to execute the tests on classes located under the
src/Joomla directory.

#### Coding Standards Analysis

In order to improve the consistency and readability of the source code,
we run a coding style analysis tool every time changes are pushed in the
repo. For new contributions we are going to be enforcing coding
standards to ensure that the coding style in the source code is
consistent. Ensuring that your code meets these standards will make the
process of code contribution smoother.

The Joomla Framework sniffer rules are written to be used with a tool
called PHP\_CodeSniffer. Please see the [PHP\_CodeSniffer Pear
Page](http://pear.php.net/package/PHP_CodeSniffer) for information on
installing PHP\_CodeSniffer on your system.

##### Running CodeSniffer

You can run the CodeSniffer by going to the framework root directory and
executing `phpcs --report=checkstyle
      --report-file=build/logs/checkstyle.xml --standard=/path/to/framework/build/phpcs/Joomla /path/to/framework`

Alternatively, if you have Ant installed on your system, you may run the
CodeSniffer by going to the framework root directory and executing
`ant phpcs`

##### Known Issues

-   None at this time


