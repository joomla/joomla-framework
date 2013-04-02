<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Input\Tests;

use Joomla\Test\Helper;

/**
 * Class to mock Joomla\Mocker\Input package of classes.
 *
 * @since  1.0
 */
class Mocker
{
	/**
	 * Array to hold mock get and set values.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $inputs;

	/**
	 * @var    \PHPUnit_Framework_TestCase
	 * @since  1.0
	 */
	private $test;

	/**
	 * Class contructor.
	 *
	 * @param   \PHPUnit_Framework_TestCase  $test  A test class.
	 */
	public function __construct(\PHPUnit_Framework_TestCase $test)
	{
		$this->inputs = array();
		$this->test = $test;
	}

	/**
	 * Creates an instance of a mock Joomla\Input\Input object.
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function createInput()
	{
		// Collect all the relevant methods in JDatabase.
		$methods = array(
			'count',
			'def',
			'get',
			'getArray',
			'getInt',
			'getMethod',
			'set',
			'serialize',
			'unserialize',
		);

		// Create the mock.
		$mockObject = $this->test->getMock(
			'\Joomla\Input\Input',
			$methods,
			// Constructor arguments.
			array(),
			// Mock class name.
			'',
			// Call original constructor.
			false
		);

		Helper::assignMockCallbacks(
			$mockObject,
			$this->test,
			array(
				'get' => array((is_callable(array($this->test, 'mockInputGet')) ? $this->test : $this), 'mockInputGet'),
				'getInt' => array((is_callable(array($this->test, 'mockInputGetInt')) ? $this->test : $this), 'mockInputGetInt'),
				'set' => array((is_callable(array($this->test, 'mockInputSet')) ? $this->test : $this), 'mockInputSet'),
			)
		);

		$mockObject->get = $mockObject;
		$mockObject->post = $mockObject;
		$mockObject->request = $mockObject;

		return $mockObject;
	}

	/**
	 * Callback for the mock get method.
	 *
	 * @param   string  $name     Name of the value to get.
	 * @param   mixed   $default  Default value to return if variable does not exist.
	 * @param   string  $filter   Filter to apply to the value.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function mockInputGet($name, $default = null, $filter = 'cmd')
	{
		return isset($this->inputs[$name]) ? $this->inputs[$name] : $default;
	}

	/**
	 * Callback for the mock getInt method.
	 *
	 * @param   string  $name     Name of the value to get.
	 * @param   mixed   $default  Default value to return if variable does not exist.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function mockInputGetInt($name, $default = null)
	{
		return (int) $this->mockInputGet($name, $default);
	}

	/**
	 * Callback for the mock set method.
	 *
	 * @param   string  $name   Name of the value to set.
	 * @param   mixed   $value  Value to assign to the input.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function mockInputSet($name, $value)
	{
		$this->inputs[$name] = $value;
	}
}
