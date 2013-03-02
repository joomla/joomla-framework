<?php
/**
 * @package     Joomla\Framework\Tests
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Application\Tests;

use Joomla\Application\Web;
use Joomla\Application\Web\Client as WebClient;
use Joomla\Registry\Registry;
use Joomla\Test\Config;
use Joomla\Test\Helper;
use Joomla\Test\WebInspector;

/**
 * Test class for Joomla\Application\Web.
 *
 * @package  Joomla\Framework\Tests
 * @since    1.0
 */
class WebTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Value for test host.
	 *
	 * @var    string
	 * @since  1.0
	 */
	const TEST_HTTP_HOST = 'mydomain.com';

	/**
	 * Value for test user agent.
	 *
	 * @var    string
	 * @since  1.0
	 */
	const TEST_USER_AGENT = 'Mozilla/5.0';

	/**
	 * Value for test user agent.
	 *
	 * @var    string
	 * @since  1.0
	 */
	const TEST_REQUEST_URI = '/index.php';

	/**
	 * An instance of the class to test.
	 *
	 * @var    WebInspector
	 * @since  1.0
	 */
	protected $class;

	/**
	 * Data for detectRequestUri method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getDetectRequestUriData()
	{
		return array(
			// HTTPS, PHP_SELF, REQUEST_URI, HTTP_HOST, SCRIPT_NAME, QUERY_STRING, (resulting uri)
			array(null, '/j/index.php', '/j/index.php?foo=bar', 'joom.la:3', '/j/index.php', '', 'http://joom.la:3/j/index.php?foo=bar'),
			array('on', '/j/index.php', '/j/index.php?foo=bar', 'joom.la:3', '/j/index.php', '', 'https://joom.la:3/j/index.php?foo=bar'),
			array(null, '', '', 'joom.la:3', '/j/index.php', '', 'http://joom.la:3/j/index.php'),
			array(null, '', '', 'joom.la:3', '/j/index.php', 'foo=bar', 'http://joom.la:3/j/index.php?foo=bar'),
		);
	}

	/**
	 * Data for fetchConfigurationData method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getRedirectData()
	{
		return array(
			// Note: url, base, request, (expected result)
			array('/foo', 'http://j.org/', 'http://j.org/index.php?v=1.0', 'http://j.org/foo'),
			array('foo', 'http://j.org/', 'http://j.org/index.php?v=1.0', 'http://j.org/foo'),
		);
	}

	/**
	 * Setup for testing.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setUp()
	{
		$_SERVER['HTTP_HOST'] = self::TEST_HTTP_HOST;
		$_SERVER['HTTP_USER_AGENT'] = self::TEST_USER_AGENT;
		$_SERVER['REQUEST_URI'] = self::TEST_REQUEST_URI;
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		// Get a new WebInspector instance.
		$this->class = new WebInspector;
	}

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   1.0
	 */
	protected function tearDown()
	{
		// Reset some web inspector static settings.
		WebInspector::$headersSent = false;
		WebInspector::$connectionAlive = true;
	}

	/**
	 * Tests the Joomla\Application\Web::__construct method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertInstanceOf(
			'Joomla\\Input\\Input',
			$this->class->input,
			'Input property wrong type'
		);

		$this->assertInstanceOf(
			'Joomla\Registry\Registry',
			Helper::getValue($this->class, 'config'),
			'Config property wrong type'
		);

		$this->assertInstanceOf(
			'Joomla\\Application\\Web\\Client',
			$this->class->client,
			'Client property wrong type'
		);

		// TODO Test that configuration data loaded.

		$this->assertThat(
			$this->class->get('execution.datetime'),
			$this->greaterThan('2001'),
			'Tests execution.datetime was set.'
		);

		$this->assertThat(
			$this->class->get('execution.timestamp'),
			$this->greaterThan(1),
			'Tests execution.timestamp was set.'
		);

		$this->assertThat(
			$this->class->get('uri.base.host'),
			$this->equalTo('http://' . self::TEST_HTTP_HOST),
			'Tests uri base host setting.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::__construct method with dependancy injection.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function test__constructDependancyInjection()
	{
		$mockInput = $this->getMock('Joomla\\Input\\Input', array('test'), array(), '', false);
		$mockInput
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$mockConfig = $this->getMock('Joomla\Registry\Registry', array('test'), array(null), '', true);
		$mockConfig
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$mockClient = $this->getMock('Joomla\\Application\\Web\\Client', array('test'), array(), '', false);
		$mockClient
			->expects($this->any())
			->method('test')
			->will(
			$this->returnValue('ok')
		);

		$inspector = new WebInspector($mockInput, $mockConfig, $mockClient);

		$this->assertThat(
			$inspector->input->test(),
			$this->equalTo('ok'),
			'Tests input injection.'
		);

		$this->assertThat(
			Helper::getValue($inspector, 'config')->test(),
			$this->equalTo('ok'),
			'Tests config injection.'
		);

		$this->assertThat(
			$inspector->client->test(),
			$this->equalTo('ok'),
			'Tests client injection.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::allowCache method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAllowCache()
	{
		$this->assertThat(
			$this->class->allowCache(),
			$this->isFalse(),
			'Return value of allowCache should be false by default.'
		);

		$this->assertThat(
			$this->class->allowCache(true),
			$this->isTrue(),
			'Return value of allowCache should return the new state.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'response')->cachable,
			$this->isTrue(),
			'Checks the internal cache property has been set.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::appendBody method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAppendBody()
	{
		// Similulate a previous call to setBody or appendBody.
		Helper::getValue($this->class, 'response')->body = array('foo');

		$this->class->appendBody('bar');

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('foo', 'bar')
			),
			'Checks the body array has been appended.'
		);

		$this->class->appendBody(true);

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('foo', 'bar', '1')
			),
			'Checks that non-strings are converted to strings.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::clearHeaders method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testClearHeaders()
	{
		// Fill the header array with an arbitrary value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => array('foo'),
				'body' => array(),
			)
		);

		$this->class->clearHeaders();

		$this->assertEquals(
			array(),
			Helper::getValue($this->class, 'response')->headers,
			'Checks the headers were cleared.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::close method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testClose()
	{
		// Make sure the application is not already closed.
		$this->assertSame(
			$this->class->closed,
			null,
			'Checks the application doesn\'t start closed.'
		);

		$this->class->close(3);

		// Make sure the application is closed with code 3.
		$this->assertSame(
			$this->class->closed,
			3,
			'Checks the application was closed with exit code 3.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::compress method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCompressWithGzipEncoding()
	{
		// Fill the header body with a value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
					veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
					dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
					sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			)
		);

		// Load the client encoding with a value.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'encodings' => array('gzip', 'deflate'),
			)
		);

		Helper::invoke($this->class, 'compress');

		// Ensure that the compressed body is shorter than the raw body.
		$this->assertThat(
			strlen($this->class->getBody()),
			$this->lessThan(471),
			'Checks the compressed output is smaller than the uncompressed output.'
		);

		// Ensure that the compression headers were set.
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(
				array(
					0 => array('name' => 'Content-Encoding', 'value' => 'gzip'),
					1 => array('name' => 'X-Content-Encoded-By', 'value' => 'Joomla')
				)
			),
			'Checks the headers were set correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::compress method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCompressWithDeflateEncoding()
	{
		// Fill the header body with a value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array('Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
					veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
					dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
					sunt in culpa qui officia deserunt mollit anim id est laborum.'),
			)
		);

		// Load the client encoding with a value.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'encodings' => array('deflate', 'gzip'),
			)
		);

		Helper::invoke($this->class, 'compress');

		// Ensure that the compressed body is shorter than the raw body.
		$this->assertThat(
			strlen($this->class->getBody()),
			$this->lessThan(471),
			'Checks the compressed output is smaller than the uncompressed output.'
		);

		// Ensure that the compression headers were set.
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(
				array(
					0 => array('name' => 'Content-Encoding', 'value' => 'deflate'),
					1 => array('name' => 'X-Content-Encoded-By', 'value' => 'Joomla')
				)
			),
			'Checks the headers were set correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::compress method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCompressWithNoAcceptEncodings()
	{
		$string = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
					veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
					dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
					sunt in culpa qui officia deserunt mollit anim id est laborum.';

		// Replace \r\n -> \n to ensure same length on all platforms
		// Fill the header body with a value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array(str_replace("\r\n", "\n", $string)),
			)
		);

		// Load the client encoding with a value.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'encodings' => array(),
			)
		);

		Helper::invoke($this->class, 'compress');

		// Ensure that the compressed body is the same as the raw body since there is no compression.
		$this->assertThat(
			strlen($this->class->getBody()),
			$this->equalTo(471),
			'Checks the compressed output is the same as the uncompressed output -- no compression.'
		);

		// Ensure that the compression headers were not set.
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(null),
			'Checks the headers were set correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::compress method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCompressWithHeadersSent()
	{
		$string = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
					veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
					dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
					sunt in culpa qui officia deserunt mollit anim id est laborum.';

		// Replace \r\n -> \n to ensure same length on all platforms
		// Fill the header body with a value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array(str_replace("\r\n", "\n", $string)),
			)
		);

		// Load the client encoding with a value.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'encodings' => array('gzip', 'deflate'),
			)
		);

		// Set the headers sent flag to true.
		WebInspector::$headersSent = true;

		Helper::invoke($this->class, 'compress');

		// Set the headers sent flag back to false.
		WebInspector::$headersSent = false;

		// Ensure that the compressed body is the same as the raw body since there is no compression.
		$this->assertThat(
			strlen($this->class->getBody()),
			$this->equalTo(471),
			'Checks the compressed output is the same as the uncompressed output -- no compression.'
		);

		// Ensure that the compression headers were not set.
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(null),
			'Checks the headers were set correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::compress method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCompressWithUnsupportedEncodings()
	{
		$string = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
					eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
					veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
					dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
					sunt in culpa qui officia deserunt mollit anim id est laborum.';

		// Replace \r\n -> \n to ensure same length on all platforms
		// Fill the header body with a value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array(str_replace("\r\n", "\n", $string)),
			)
		);

		// Load the client encoding with a value.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'encodings' => array('foo', 'bar'),
			)
		);

		Helper::invoke($this->class, 'compress');

		// Ensure that the compressed body is the same as the raw body since there is no supported compression.
		$this->assertThat(
			strlen($this->class->getBody()),
			$this->equalTo(471),
			'Checks the compressed output is the same as the uncompressed output -- no supported compression.'
		);

		// Ensure that the compression headers were not set.
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(null),
			'Checks the headers were set correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::detectRequestUri method.
	 *
	 * @param   string  $https        @todo
	 * @param   string  $phpSelf      @todo
	 * @param   string  $requestUri   @todo
	 * @param   string  $httpHost     @todo
	 * @param   string  $scriptName   @todo
	 * @param   string  $queryString  @todo
	 * @param   string  $expects      @todo
	 *
	 * @return  void
	 *
	 * @dataProvider getDetectRequestUriData
	 * @since   1.0
	 */
	public function testDetectRequestUri($https, $phpSelf, $requestUri, $httpHost, $scriptName, $queryString, $expects)
	{
		if ($https !== null)
		{
			$_SERVER['HTTPS'] = $https;
		}

		$_SERVER['PHP_SELF'] = $phpSelf;
		$_SERVER['REQUEST_URI'] = $requestUri;
		$_SERVER['HTTP_HOST'] = $httpHost;
		$_SERVER['SCRIPT_NAME'] = $scriptName;
		$_SERVER['QUERY_STRING'] = $queryString;

		$this->assertThat(
			Helper::invoke($this->class, 'detectRequestUri'),
			$this->equalTo($expects)
		);
	}

	/**
	 * Data for fetchConfigurationData method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getFetchConfigurationData()
	{
		return array(
			// Note: file, class, expectsClass, (expected result array), whether there should be an exception
			'Default configuration class' => array(null, null, '\\Joomla\\Test\\Config', 'ConfigEval'),
			'Custom file, invalid class' => array(JPATH_BASE . '/config.JCli-wrongclass.php', 'noclass', false, array(), true),
		);
	}

	/**
	 * Tests the JCli::fetchConfigurationData method.
	 *
	 * @param   string   $file               The name of the configuration file.
	 * @param   string   $class              The name of the class.
	 * @param   boolean  $expectsClass       The result is expected to be a class.
	 * @param   array    $expects            The expected result as an array.
	 * @param   bool     $expectedException  The expected exception.
	 *
	 * @return  void
	 *
	 * @dataProvider getFetchConfigurationData
	 * @since    1.0
	 */
	public function testFetchConfigurationData($file, $class, $expectsClass, $expects, $expectedException = false)
	{
		if ($expectedException)
		{
			$this->setExpectedException('RuntimeException');
		}

		if (is_null($file) && is_null($class))
		{
			$config = Helper::invoke($this->class, 'fetchConfigurationData');
		}
		elseif (is_null($class))
		{
			$config = Helper::invoke($this->class, 'fetchConfigurationData', $file);
		}
		else
		{
			$config = Helper::invoke($this->class, 'fetchConfigurationData', $file, $class);
		}

		if ($expects == 'ConfigEval')
		{
			$expects = new Config;
			$expects = (array) $expects;
		}

		if ($expectsClass)
		{
			$this->assertInstanceOf(
				$expectsClass,
				$config,
				'Checks the configuration object is the appropriate class.'
			);
		}

		$this->assertThat(
			(array) $config,
			$this->equalTo($expects),
			'Checks the content of the configuration object.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::get method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGet()
	{
		$config = new Registry(array('foo' => 'bar'));

		Helper::setValue($this->class, 'config', $config);

		$this->assertThat(
			$this->class->get('foo', 'car'),
			$this->equalTo('bar'),
			'Checks a known configuration setting is returned.'
		);

		$this->assertThat(
			$this->class->get('goo', 'car'),
			$this->equalTo('car'),
			'Checks an unknown configuration setting returns the default.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::getBody method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetBody()
	{
		// Fill the header body with an arbitrary value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => null,
				'body' => array('foo', 'bar'),
			)
		);

		$this->assertThat(
			$this->class->getBody(),
			$this->equalTo('foobar'),
			'Checks the default state returns the body as a string.'
		);

		$this->assertThat(
			$this->class->getBody(),
			$this->equalTo($this->class->getBody(false)),
			'Checks the default state is $asArray = false.'
		);

		$this->assertThat(
			$this->class->getBody(true),
			$this->equalTo(array('foo', 'bar')),
			'Checks that the body is returned as an array.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::getHeaders method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetHeaders()
	{
		// Fill the header body with an arbitrary value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => array('ok'),
				'body' => null,
			)
		);

		$this->assertThat(
			$this->class->getHeaders(),
			$this->equalTo(array('ok')),
			'Checks the headers part of the response is returned correctly.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		$this->assertInstanceOf(
			'\\Joomla\\Test\\WebInspector',
			Web::getInstance('Joomla\Test\WebInspector'),
			'Tests that getInstance will instantiate a valid child class of Joomla\Application\Web.'
		);

		Helper::setValue('Joomla\Test\WebInspector', 'instance','foo');

		$this->assertThat(
			Web::getInstance('Joomla\Test\WebInspector'),
			$this->equalTo('foo'),
			'Tests that singleton value is returned.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::getInstance method for an expected exception
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @expectedException  RuntimeException
	 */
	public function testGetInstanceException()
	{
		Helper::setValue($this->class, 'instance', null);

		Web::getInstance('Foo');
	}

	/**
	 * Tests the Joomla\Application\Web::loadConfiguration method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadConfiguration()
	{
		$this->assertThat(
			$this->class->loadConfiguration(
				array(
					'foo' => 'bar',
				)
			),
			$this->identicalTo($this->class),
			'Check chaining.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('foo'),
			$this->equalTo('bar'),
			'Check the configuration array was loaded.'
		);

		$this->class->loadConfiguration(
			(object) array(
				'goo' => 'car',
			)
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('goo'),
			$this->equalTo('car'),
			'Check the configuration object was loaded.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::loadLanguage method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadLanguage()
	{
		$this->class->loadLanguage();

		$this->assertInstanceOf(
			'\\Joomla\\Language\\Language',
			Helper::getValue($this->class, 'language'),
			'Tests that the language object is the correct class.'
		);

		/* TODO: There's no test method...
		$this->assertThat(
			Helper::getValue($this->class, 'language')->test(),
			$this->equalTo('ok'),
			'Tests that we got the language from the factory.'
		); */
	}

	/**
	 * Tests the Joomla\Application\Web::loadSession method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadSession()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Application\Web::loadSystemUris method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadSystemUrisWithSiteUriSet()
	{
		// Set the site_uri value in the configuration.
		$config = new Registry(array('site_uri' => 'http://test.joomla.org/path/'));
		Helper::setValue($this->class, 'config', $config);

		Helper::invoke($this->class, 'loadSystemUris');

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.full'),
			$this->equalTo('http://test.joomla.org/path/'),
			'Checks the full base uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.host'),
			$this->equalTo('http://test.joomla.org'),
			'Checks the base uri host.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.path'),
			$this->equalTo('/path/'),
			'Checks the base uri path.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.full'),
			$this->equalTo('http://test.joomla.org/path/media/'),
			'Checks the full media uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.path'),
			$this->equalTo('/path/media/'),
			'Checks the media uri path.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::loadSystemUris method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadSystemUrisWithoutSiteUriSet()
	{
		Helper::invoke($this->class, 'loadSystemUris', 'http://joom.la/application');

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.full'),
			$this->equalTo('http://joom.la/'),
			'Checks the full base uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.host'),
			$this->equalTo('http://joom.la'),
			'Checks the base uri host.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.path'),
			$this->equalTo('/'),
			'Checks the base uri path.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.full'),
			$this->equalTo('http://joom.la/media/'),
			'Checks the full media uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.path'),
			$this->equalTo('/media/'),
			'Checks the media uri path.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::loadSystemUris method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadSystemUrisWithoutSiteUriWithMediaUriSet()
	{
		// Set the media_uri value in the configuration.
		$config = new Registry(array('media_uri' => 'http://cdn.joomla.org/media/'));
		Helper::setValue($this->class, 'config', $config);

		Helper::invoke($this->class, 'loadSystemUris', 'http://joom.la/application');

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.full'),
			$this->equalTo('http://joom.la/'),
			'Checks the full base uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.host'),
			$this->equalTo('http://joom.la'),
			'Checks the base uri host.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.path'),
			$this->equalTo('/'),
			'Checks the base uri path.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.full'),
			$this->equalTo('http://cdn.joomla.org/media/'),
			'Checks the full media uri.'
		);

		// Since this is on a different domain we need the full url for this too.
		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.path'),
			$this->equalTo('http://cdn.joomla.org/media/'),
			'Checks the media uri path.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::loadSystemUris method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testLoadSystemUrisWithoutSiteUriWithRelativeMediaUriSet()
	{
		// Set the media_uri value in the configuration.
		$config = new Registry(array('media_uri' => '/media/'));
		Helper::setValue($this->class, 'config', $config);

		Helper::invoke($this->class, 'loadSystemUris', 'http://joom.la/application');

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.full'),
			$this->equalTo('http://joom.la/'),
			'Checks the full base uri.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.host'),
			$this->equalTo('http://joom.la'),
			'Checks the base uri host.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.base.path'),
			$this->equalTo('/'),
			'Checks the base uri path.'
		);

		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.full'),
			$this->equalTo('http://joom.la/media/'),
			'Checks the full media uri.'
		);

		// Since this is on a different domain we need the full url for this too.
		$this->assertThat(
			Helper::getValue($this->class, 'config')->get('uri.media.path'),
			$this->equalTo('/media/'),
			'Checks the media uri path.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::prependBody method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testPrependBody()
	{
		// Similulate a previous call to a body method.
		Helper::getValue($this->class, 'response')->body = array('foo');

		$this->class->prependBody('bar');

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('bar', 'foo')
			),
			'Checks the body array has been prepended.'
		);

		$this->class->prependBody(true);

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('1', 'bar', 'foo')
			),
			'Checks that non-strings are converted to strings.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::redirect method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRedirect()
	{
		$base = 'http://j.org/';
		$url = 'index.php';

		// Inject the client information.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'engine' => WebClient::GECKO,
			)
		);

		// Inject the internal configuration.
		$config = new Registry;
		$config->set('uri.base.full', $base);

		Helper::setValue($this->class, 'config', $config);

		$this->class->redirect($url, false);

		$this->assertThat(
			$this->class->headers,
			$this->equalTo(
				array(
					array('HTTP/1.1 303 See other', true, null),
					array('Location: ' . $base . $url, true, null),
					array('Content-Type: text/html; charset=utf-8', true, null),
				)
			)
		);
	}

	/**
	 * Tests the Joomla\Application\Web::redirect method with headers already sent.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRedirectWithHeadersSent()
	{
		$base = 'http://j.org/';
		$url = 'index.php';

		// Emulate headers already sent.
		WebInspector::$headersSent = true;

		// Inject the internal configuration.
		$config = new Registry;
		$config->set('uri.base.full', $base);

		Helper::setValue($this->class, 'config', $config);

		// Capture the output for this test.
		ob_start();
		$this->class->redirect('index.php');
		$buffer = ob_get_contents();
		ob_end_clean();

		$this->assertThat(
			$buffer,
			$this->equalTo("<script>document.location.href='{$base}{$url}';</script>\n")
		);
	}

	/**
	 * Tests the Joomla\Application\Web::redirect method with headers already sent.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRedirectWithJavascriptRedirect()
	{
		$url = 'http://j.org/index.php?phi=Φ';

		// Inject the client information.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'engine' => WebClient::TRIDENT,
			)
		);

		// Capture the output for this test.
		ob_start();
		$this->class->redirect($url);
		$buffer = ob_get_contents();
		ob_end_clean();

		$this->assertThat(
			trim($buffer),
			$this->equalTo(
				'<html><head>'
					. '<meta http-equiv="content-type" content="text/html; charset=utf-8" />'
					. "<script>document.location.href='{$url}';</script>"
					. '</head><body></body></html>'
			)
		);
	}

	/**
	 * Tests the Joomla\Application\Web::redirect method with moved option.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRedirectWithMoved()
	{
		$url = 'http://j.org/index.php';

		// Inject the client information.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'engine' => WebClient::GECKO,
			)
		);

		$this->class->redirect($url, true);

		$this->assertThat(
			$this->class->headers,
			$this->equalTo(
				array(
					array('HTTP/1.1 301 Moved Permanently', true, null),
					array('Location: ' . $url, true, null),
					array('Content-Type: text/html; charset=utf-8', true, null),
				)
			)
		);
	}

	/**
	 * Tests the Joomla\Application\Web::redirect method with assorted URL's.
	 *
	 * @param   string  $url       @todo
	 * @param   string  $base      @todo
	 * @param   string  $request   @todo
	 * @param   string  $expected  @todo
	 *
	 * @return  void
	 *
	 * @dataProvider  getRedirectData
	 * @since   1.0
	 */
	public function testRedirectWithUrl($url, $base, $request, $expected)
	{
		// Inject the client information.
		Helper::setValue(
			$this->class,
			'client',
			(object) array(
				'engine' => WebClient::GECKO,
			)
		);

		// Inject the internal configuration.
		$config = new Registry;
		$config->set('uri.base.full', $base);
		$config->set('uri.request', $request);

		Helper::setValue($this->class, 'config', $config);

		$this->class->redirect($url, false);

		$this->assertThat(
			$this->class->headers[1][0],
			$this->equalTo('Location: ' . $expected)
		);
	}

	/**
	 * Tests the Joomla\Application\Web::respond method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testRespond()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Application\Web::sendHeaders method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSendHeaders()
	{
		// Similulate a previous call to a setHeader method.
		Helper::getValue($this->class, 'response')->headers = array(
			array('name' => 'Status', 'value' => 200),
			array('name' => 'X-JWeb-SendHeaders', 'value' => 'foo'),
		);

		$this->assertThat(
			$this->class->sendHeaders(),
			$this->identicalTo($this->class),
			'Check chaining.'
		);

		$this->assertThat(
			$this->class->headers,
			$this->equalTo(
				array(
					array('Status: 200', null, 200),
					array('X-JWeb-SendHeaders: foo', true, null),
				)
			)
		);
	}

	/**
	 * Tests the Joomla\Application\Web::set method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSet()
	{
		$config = new Registry(array('foo' => 'bar'));

		Helper::setValue($this->class, 'config', $config);

		$this->assertThat(
			$this->class->set('foo', 'car'),
			$this->equalTo('bar'),
			'Checks set returns the previous value.'
		);

		$this->assertThat(
			$config->get('foo'),
			$this->equalTo('car'),
			'Checks the new value has been set.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::setBody method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetBody()
	{
		$this->class->setBody('foo');

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('foo')
			),
			'Checks the body array has been reset.'
		);

		$this->class->setBody(true);

		$this->assertThat(
			Helper::getValue($this->class, 'response')->body,
			$this->equalTo(
				array('1')
			),
			'Checks reset and that non-strings are converted to strings.'
		);
	}

	/**
	 * Tests the Joomla\Application\Web::setHeader method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testSetHeader()
	{
		// Fill the header body with an arbitrary value.
		Helper::setValue(
			$this->class,
			'response',
			(object) array(
				'cachable' => null,
				'headers' => array(
					array('name' => 'foo', 'value' => 'bar'),
				),
				'body' => null,
			)
		);

		$this->class->setHeader('foo', 'car');
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(
				array(
					array('name' => 'foo', 'value' => 'bar'),
					array('name' => 'foo', 'value' => 'car')
				)
			),
			'Tests that a header is added.'
		);

		$this->class->setHeader('foo', 'car', true);
		$this->assertThat(
			Helper::getValue($this->class, 'response')->headers,
			$this->equalTo(
				array(
					array('name' => 'foo', 'value' => 'car')
				)
			),
			'Tests that headers of the same name are replaced.'
		);
	}

	/**
	 * Test...
	 *
	 * @covers Joomla\Application\Web::isSSLConnection
	 *
	 * @return void
	 */
	public function testIsSSLConnection()
	{
		unset($_SERVER['HTTPS']);

		$this->assertThat(
			$this->class->isSSLConnection(),
			$this->equalTo(false)
		);

		$_SERVER['HTTPS'] = 'on';

		$this->assertThat(
			$this->class->isSSLConnection(),
			$this->equalTo(true)
		);
	}
}
