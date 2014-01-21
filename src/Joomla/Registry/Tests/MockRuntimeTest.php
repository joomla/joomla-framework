<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


require_once(__DIR__.'/Stubs/MockRuntime.php');

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
	 * Get a new Runtime Registry Object
	 *
	 * @return  Runtime
	 *
	 * @since   1.0
	 */
	private function createRegistry($arg = null)
	{
		if ($arg === null)
		{
			return new MockRuntime();
		}
		return new MockRuntime($arg);
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


}
