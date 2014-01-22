<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


require_once __DIR__ . '/RuntimeTest.php';
require_once __DIR__ . '/Stubs/MockRuntime.php';

use Joomla\Registry\Tests\MockRuntime;
use Joomla\Registry\Runtime;
use Joomla\Test\TestHelper;
use Joomla\Registry\Registry;

/**
 * Test class for MockRuntime Registry - important to check the Mock Runtime stub
 * because other packages need to depened on it..
 *
 * @since  1.0
 */
class MockRuntimeRegistryTest extends RuntimeRegistryTest
{
	/**
	 * Registry instances container.
	 *
	 * @var    array
	 * @since  1.0
	 */
	protected static $instances = array();

	/**
	 * Returns a reference to a global Registry object, only creating it
	 * if it doesn't already exist.
	 *
	 * This method must be invoked as:
	 * <pre>$registry = Registry::getInstance($id);</pre>
	 *
	 * @param   string  $id  An ID for the registry instance
	 *
	 * @return  Registry  The Registry object.
	 *
	 * @since   1.0
	 */
	public static function getInstance($id)
	{
		if (empty(static::$instances[$id]))
		{
			static::$instances[$id] = new MockRuntime;
		}

		return static::$instances[$id];
	}

	/**
	 * Get a new Runtime Registry Object
	 *
	 * @param	$arg	Registry data
	 *
	 * @return  MockRuntime
	 *
	 * @since   1.0
	 */
	protected function createRegistry($arg = null)
	{
		if ($arg === null)
		{
			return new MockRuntime;
		}
		return new MockRuntime($arg);
	}

	/**
	 * Test the Joomla\Registry\Runtime::getInstance method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Runtime::getInstance
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		// Test INI format.
		$a = MockRuntime::getInstance('a');
		$b = MockRuntime::getInstance('a');
		$c = MockRuntime::getInstance('c');

		// Check the object type.
		$this->assertThat(
			$a instanceof Joomla\Registry\Registry,
			$this->isTrue(),
			'Line: ' . __LINE__ . '.'
		);

		// Check the object type.
		$this->assertThat(
			$a instanceof Joomla\Registry\Runtime,
			$this->isTrue(),
			'Line: ' . __LINE__ . '.'
		);

		// Check the object type.
		$this->assertThat(
			$a instanceof Joomla\Registry\Tests\MockRuntime,
			$this->isTrue(),
			'Line: ' . __LINE__ . '.'
		);

		// Check cache handling for same registry id.
		$this->assertThat(
			$a,
			$this->identicalTo($b),
			'Line: ' . __LINE__ . '.'
		);

		// Check cache handling for different registry id.
		$this->assertThat(
			$a,
			$this->logicalNot($this->identicalTo($c)),
			'Line: ' . __LINE__ . '.'
		);
	}

	/**
	 * Test the Joomla\Registry\Runtime::exists method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Runtime::exists
	 * @since   1.0
	 */
	public function testExists()
	{
		// Must pass all parents existence tests
		parent::testExists();
		$this->markTestIncomplete('Did not test class specific exists calls');
	}

	/**
	 * Test the Joomla\Registry\Runtime::checkExtension method.
	 *
	 * @param	string $validItem	a valid item in the list
	 * @param	string	$invalidItem	an invalid item in the list
	 * @param	string	$method	the check method to run
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCheck($validItem = false, $invalidItem = false, $method = false)
	{
		if (!$method)
		{
			return;
		}

		/** @var Joomla\Registry\Runtime $instance */
		$instance =& $this->instance;
		$classname = get_class($instance);

		if ($validItem)
		{
			$instance->setReturn(true);
			$result = $classname::$method($validItem);

			// Check the object type.
			$this->assertThat(
				$result,
				$this->isTrue(),
				'Class: ' . $classname . ' Method: ' . $method . ' Item: ' . $validItem . ' Line: ' . __LINE__ . '.'
			);
		}
		else
		{
			$this->markTestIncomplete('No valid item to test');
		}

		if ($invalidItem)
		{
			$instance->setReturn(false);
			$result = $classname::$method($invalidItem);
			$this->assertThat(
				$result,
				$this->isFalse(),
				'Class: ' . $classname . ' Method: ' . $method . ' Item: ' . $invalidItem . ' Line: ' . __LINE__ . '.'
			);
		}
		else
		{
			$this->markTestIncomplete('No invalid item to test');
		}
	}
}
