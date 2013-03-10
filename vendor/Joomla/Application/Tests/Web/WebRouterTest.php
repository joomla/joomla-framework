<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/Stubs/Bar.php';
require_once __DIR__ . '/Stubs/Baz.php';
require_once __DIR__ . '/Stubs/Foo.php';

/**
 * Test class for JApplicationWebRouter.
 *
 * @since    1.0
 */
class JApplicationWebRouterTest extends TestCase
{
	/**
	 * @var    JApplicationWebRouter  The object to be tested.
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$this->assertAttributeInstanceOf('Joomla\\Application\\Web', 'app', $this->instance);
		$this->assertAttributeInstanceOf('Joomla\\Input\\Input', 'input', $this->instance);
	}

	/**
	 * Tests the setControllerPrefix method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::setControllerPrefix
	 * @since   1.0
	 */
	public function testSetControllerPrefix()
	{
		$this->instance->setControllerPrefix('MyApplication');
		$this->assertAttributeEquals('MyApplication', 'controllerPrefix', $this->instance);
	}

	/**
	 * Tests the setDefaultController method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::setDefaultController
	 * @since   1.0
	 */
	public function testSetDefaultController()
	{
		$this->instance->setDefaultController('foobar');
		$this->assertAttributeEquals('foobar', 'default', $this->instance);
	}

	/**
	 * Tests the fetchController method if the controller class is missing.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithMissingClass()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestReflection::invoke($this->instance, 'fetchController', 'goober');
	}

	/**
	 * Tests the fetchController method if the class not a controller.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithNonController()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestReflection::invoke($this->instance, 'fetchController', 'MyTestControllerBaz');
	}

	/**
	 * Tests the fetchController method with a prefix set.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithPrefixSet()
	{
		TestReflection::setValue($this->instance, 'controllerPrefix', 'MyTestController');
		$controller = TestReflection::invoke($this->instance, 'fetchController', 'foo');
	}

	/**
	 * Tests the fetchController method without a prefix set even though it is necessary.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithoutPrefixSetThoughNecessary()
	{
		$this->setExpectedException('RuntimeException');
		$controller = TestReflection::invoke($this->instance, 'fetchController', 'foo');
	}

	/**
	 * Tests the fetchController method without a prefix set.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Application\Web\Router::fetchController
	 * @since   1.0
	 */
	public function testFetchControllerWithoutPrefixSet()
	{
		$controller = TestReflection::invoke($this->instance, 'fetchController', 'TControllerBar');
	}

	/**
	 * Prepares the environment before running a test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = $this->getMockForAbstractClass('Joomla\\Application\\Web\\Router', array($this->getMockWeb()));
	}

	/**
	 * Cleans up the environment after running a test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
		$this->instance = null;

		parent::tearDown();
	}
}
