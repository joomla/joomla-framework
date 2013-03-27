<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller\Tests;

use Joomla\Application\Tests\Mock as ApplicationMock;
use Joomla\Input\Input;
use Joomla\Input\Cookie as InputCookie;
use Joomla\Test\Helper;

require_once __DIR__ . '/Stubs/BaseController.php';

/**
 * Tests for the Joomla\Controller\Base class.
 *
 * @since  1.0
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
	 * @covers  Joomla\Controller\Base::getInput
	 * @covers  Joomla\Controller\Base::getApplication
	 * @since   1.0
	 */
	public function test__construct()
	{
		$app = Helper::getValue($this->instance, 'app');

		// New controller with no dependancies.
		$this->assertAttributeEmpty('input', $this->instance);
		$this->assertAttributeEmpty('app', $this->instance);

		// New controller with dependancies
		$app = ApplicationMock\Base::create($this);
		$input = new InputCookie;

		$instance = new BaseController($input, $app);
		$this->assertSame($input, $instance->getInput());
		$this->assertSame($app, $instance->getApplication());
	}

	/**
	 * Tests the getApplication method for a known exception
	 *
	 * @return  void
	 *
	 * @covers             Joomla\Controller\Base::getApplication
	 * @expectedException  \UnexpectedValueException
	 * @since              1.0
	 */
	public function testGetApplication_exception()
	{
		$this->instance->getApplication();
	}

	/**
	 * Tests the getInput method for a known exception
	 *
	 * @return  void
	 *
	 * @covers             Joomla\Controller\Base::getInput
	 * @expectedException  \UnexpectedValueException
	 * @since              1.0
	 */
	public function testGetInput_exception()
	{
		$this->instance->getInput();
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
		$this->instance->setInput(new InputCookie);

		$this->assertContains('C:19:"Joomla\Input\Cookie"', $this->instance->serialize());
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
		$this->assertInstanceOf('\Joomla\Input\Input', $this->instance->getInput());
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
	 * Tests the setApplication method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::setApplication
	 * @since   1.0
	 */
	public function testSetApplication()
	{
		$app = ApplicationMock\Base::create($this);
		$this->instance->setApplication($app);
		$this->assertSame($app, $this->instance->getApplication());
	}

	/**
	 * Tests the setInput method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Controller\Base::setInput
	 * @since   1.0
	 */
	public function testSetInput()
	{
		$input = new InputCookie;
		$this->instance->setInput($input);
		$this->assertSame($input, $this->instance->getInput());
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

		$this->instance = new BaseController;
	}
}
