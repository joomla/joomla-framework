<?php
/**
 * @package    Joomla\Framework\Test
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller\Tests;

use Joomla\Application\Tests\Mock as ApplicationMock;
use Joomla\Factory;
use Joomla\Input\Input;
use Joomla\Input\Cookie as InputCookie;
use Joomla\Test\Helper;

require_once __DIR__ . '/Stubs/BaseController.php';

/**
 * Tests for the Joomla\Controller\Base class.
 *
 * @package  Joomla\Framework\Test
 * @since    1.0
 */
class BaseTest extends \TestCase
{
	/**
	 * @var    \Joomla\Controller\Base
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the __construct method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::__construct
	 * @since   1.0
	 */
	public function test__construct()
	{
		$app = Helper::getValue($this->instance, 'app');

		// New controller with no dependancies.
		$this->assertAttributeEquals('default', 'input', $app, 'Checks the mock application came from the factory.');
		$this->assertAttributeEquals('default', 'input', $this->instance, 'Checks the input came from the application.');

		// New controller with dependancies
		$app = ApplicationMock\Base::create($this);
		$app->test = 'ok';

		$class = new BaseController(new InputCookie, $app);
		$this->assertAttributeInstanceOf('Joomla\Input\Cookie', 'input', $class, 'Checks the type of the injected input.');
		$this->assertAttributeSame($app, 'app', $class, 'Checks the injected application.');
	}

	/**
	 * Tests the getApplication method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::getApplication
	 * @since   1.0
	 */
	public function testGetApplication()
	{
		Helper::setValue($this->instance, 'app', 'appValue');

		$this->assertEquals('appValue', $this->instance->getApplication());
	}

	/**
	 * Tests the getInput method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::getInput
	 * @since   1.0
	 */
	public function testGetInput()
	{
		Helper::setValue($this->instance, 'input', 'inputValue');

		$this->assertEquals('inputValue', $this->instance->getInput());
	}

	/**
	 * Tests the serialize method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::serialize
	 * @since   1.0
	 */
	public function testSerialise()
	{
		$this->assertEquals('s:7:"default";', $this->instance->serialize());
	}

	/**
	 * Tests the unserialize method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::unserialize
	 * @since   1.0
	 */
	public function testUnserialise()
	{
		$input = serialize(new Input);

		$this->assertSame($this->instance, $this->instance->unserialize($input), 'Checks chaining and target method.');
		$this->assertInstanceOf('Joomla\Input\Input', $this->instance->getInput());
	}

	/**
	 * Tests the unserialize method for an expected exception.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::unserialize
	 * @since   1.0
	 *
	 * @expectedException  UnexpectedValueException
	 */
	public function testUnserialise_exception()
	{
		$this->instance->unserialize('s:7:"default";');
	}

	/**
	 * Tests the loadApplication method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::loadApplication
	 * @since   1.0
	 */
	public function testLoadApplication()
	{
		Factory::$application = 'application';

		$this->assertEquals('application', Helper::invoke($this->instance, 'loadApplication'));
	}

	/**
	 * Tests the loadInput method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::loadInput
	 * @since   1.0
	 */
	public function testLoadInput()
	{
		// Reset the input property so we know it changes based on the mock application.
		Helper::setValue($this->instance, 'input', null);

		$this->assertEquals('default', Helper::invoke($this->instance, 'loadInput'));
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->saveFactoryState();

		$app = ApplicationMock\Base::create($this);
		$app->input = 'default';

		Factory::$application = $app;

		$this->instance = new BaseController;
	}

	/**
	 * Method to tear down whatever was set up before the test.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();

		parent::teardown();
	}
}
