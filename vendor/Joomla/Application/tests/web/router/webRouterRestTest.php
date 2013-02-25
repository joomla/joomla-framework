<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Test class for JApplicationWebRouterRest.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Application
 * @since       12.3
 */
class JApplicationWebRouterRestTest extends TestCase
{
	/**
	 * @var    Joomla\Application\Web\Router\Rest  The object to be tested.
	 * @since  12.3
	 */
	private $instance;

	/**
	 * @var    string  The server REQUEST_METHOD cached to keep it clean.
	 * @since  12.3
	 */
	private $requestMethod;

	/**
	 * Tests the setHttpMethodSuffix method.
	 *
	 * @return  void
	 *
	 * @covers  JApplicationWebRouterRest::setHttpMethodSuffix
	 * @since   12.3
	 */
	public function testSetHttpMethodSuffix()
	{
		$this->instance->setHttpMethodSuffix('FOO', 'Bar');
		$s = TestReflection::getValue($this->instance, 'suffixMap');
		$this->assertEquals('Bar', $s['FOO']);
	}

	/**
	 * Tests the fetchControllerSuffix method if the suffix map is missing.
	 *
	 * @return  void
	 *
	 * @covers  JApplicationWebRouterRest::fetchControllerSuffix
	 * @since   12.3
	 */
	public function testFetchControllerSuffixWithMissingSuffixMap()
	{
		$_SERVER['REQUEST_METHOD'] = 'FOOBAR';

		$this->setExpectedException('RuntimeException');
		$suffix = TestReflection::invoke($this->instance, 'fetchControllerSuffix');
	}

	/**
	 * Provides test data for testing fetch controller sufix
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function seedFetchControllerSuffixData()
	{
		// Input, Expected
		return array(
			// Don't allow method in POST request
			array('GET', 'Get', null, false),
			array('POST', 'Create', "get", false),
			array('POST', 'Create', null, false),
			array('POST', 'Create', "post", false),
			array('PUT', 'Update', null, false),
			array('POST', 'Create', "put", false),
			array('PATCH', 'Update', null, false),
			array('POST', 'Create', "patch", false),
			array('DELETE', 'Delete', null, false),
			array('POST', 'Create', "delete", false),
			array('HEAD', 'Head', null, false),
			array('POST', 'Create', "head", false),
			array('OPTIONS', 'Options', null, false),
			array('POST', 'Create', "options", false),
			array('POST', 'Create', "foo", false),
			array('FOO', 'Create', "foo", true),

			// Allow method in POST request
			array('GET', 'Get', null, false, true),
			array('POST', 'Get', "get", false, true),
			array('POST', 'Create', null, false, true),
			array('POST', 'Create', "post", false, true),
			array('PUT', 'Update', null, false, true),
			array('POST', 'Update', "put", false, true),
			array('PATCH', 'Update', null, false, true),
			array('POST', 'Update', "patch", false, true),
			array('DELETE', 'Delete', null, false, true),
			array('POST', 'Delete', "delete", false, true),
			array('HEAD', 'Head', null, false, true),
			array('POST', 'Head', "head", false, true),
			array('OPTIONS', 'Options', null, false, true),
			array('POST', 'Options', "options", false, true),
			array('POST', 'Create', "foo", false, true),
			array('FOO', 'Create', "foo", true, true),
		);
	}

	/**
	 * Tests the fetchControllerSuffix method.
	 *
	 * @param   string   $input        Input string to test.
	 * @param   string   $expected     Expected fetched string.
	 * @param   mixed    $method       Method to override POST request
	 * @param   boolean  $exception    True if an RuntimeException is expected based on invalid input
	 * @param   boolean  $allowMethod  Allow or not to pass method in post request as parameter
	 *
	 * @return  void
	 *
	 * @covers        JApplicationWebRouterRest::fetchControllerSuffix
	 * @dataProvider  seedFetchControllerSuffixData
	 * @since         12.3
	 */
	public function testFetchControllerSuffix($input, $expected, $method, $exception, $allowMethod=false)
	{
		TestReflection::invoke($this->instance, 'setMethodInPostRequest', $allowMethod);

		// Set reuqest method
		$_SERVER['REQUEST_METHOD'] = $input;

		// Set method in POST request
		$_GET['_method'] = $method;

		// If we are expecting an exception set it.
		if ($exception)
		{
			$this->setExpectedException('RuntimeException');
		}

		// Execute the code to test.
		$actual = TestReflection::invoke($this->instance, 'fetchControllerSuffix');

		// Verify the value.
		$this->assertEquals($expected, $actual);
	}

	/**
	 * Tests the setMethodInPostRequest and isMethodInPostRequest.
	 *
	 * @return  void
	 *
	 * @covers  JApplicationWebRouterRest::setMethodInPostRequest
	 * @covers  JApplicationWebRouterRest::isMethodInPostRequest
	 * @since   12.3
	 */
	public function testMethodInPostRequest()
	{
		// Check the defaults
		$this->assertEquals(false, TestReflection::invoke($this->instance, 'isMethodInPostRequest'));

		// Check setting true
		TestReflection::invoke($this->instance, 'setMethodInPostRequest', true);
		$this->assertEquals(true, TestReflection::invoke($this->instance, 'isMethodInPostRequest'));

		// Check setting false
		TestReflection::invoke($this->instance, 'setMethodInPostRequest', false);
		$this->assertEquals(false, TestReflection::invoke($this->instance, 'isMethodInPostRequest'));
	}

	/**
	 * Prepares the environment before running a test.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new Joomla\Application\Web\Router\Rest($this->getMockWeb());
		$this->requestMethod = @$_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Cleans up the environment after running a test.
	 *
	 * @return  void
	 *
	 * @since   12.3
	 */
	protected function tearDown()
	{
		$this->instance = null;
		$_SERVER['REQUEST_METHOD'] = $this->requestMethod;

		parent::tearDown();
	}
}
