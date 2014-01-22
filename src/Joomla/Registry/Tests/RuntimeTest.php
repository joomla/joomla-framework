<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once(__DIR__.'/RegistryTest.php');

use Joomla\Registry\Runtime;
use Joomla\Test\TestHelper;
use Joomla\Registry\Registry;

/**
 * Test class for Runtime Registry.
 *
 * @since  1.0
 */
class RuntimeRegistryTest extends RegistryTest
{

	/**
	 * mockRuntime
	 *   The mock runtime object
	 *
	 * @var    boolean
	 * @since  1.1
	 */
	public $mockRuntime;



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
			return new Runtime;
		}
		return new Runtime($arg);
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
	 * @var	string $validItem	a valid item in the list
	 * @var	string	$nvalidItem	an invalid item in the list
	 * @var	string	$method	the check method to run
	 *
	 * @return  void
	 * @since   1.0
	 */
	private function testCheck($validItem, $invalidItem, $method)
	{
		/** @var Joomla\Registry\Runtime $instance */
		$instance =& $this->instance;

		$result = $instance->$method($validItem);
		// Check the object type.
		$this->assertThat(
			$result,
			$this->isTrue(),
			'Method: ' . $method . ' Item: ' . $validItem . ' Line: ' . __LINE__ . '.'
		);

		$result = $instance->$method($invalidItem);
		$this->assertThat(
			$result,
			$this->isFalse(),
			'Method: ' . $method . ' Item: ' . $invalidItem . ' Line: ' . __LINE__ . '.'
		);

	}

	/**
	 * Gets some test extension strings
	 *
	 * @return  \StdClass an object with the item strings
	 * @since   1.0
	 */
	private function getExtensionTests()
	{

		$validItems = get_loaded_extensions();
		$validItem = array_pop($validItems);
		$invalidItem = $validItem;
		while (in_array($invalidItem, $validItems))
		{
			$invalidItem = str_shuffle($invalidItem);
		}

		$item = new \StdClass;
		$item->validItems = $validItems;
		$item->validItem = $validItem;
		$item->invalidItem = $invalidItem;

		return $item;

	}


	/**
	 * Test the Joomla\Registry\Runtime::checkExtension method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Runtime::checkExtension
	 * @since   1.0
	 */
	public function testCheckExtension()
	{

		$item = $this->getExtensionTests();
		$this->testCheck($item->validItem, $item->invalidItem, 'checkExtension');

	}


	/**
	 * Gets some test function strings
	 *
	 * @return  \StdClass an object with the item strings
	 * @since   1.0
	 */
	private function getFunctionTests($type = 'internal')
	{

		$validItemsM = get_defined_functions();
		$validItems = $validItemsM[$type];
		$validItem = array_pop($validItems);
		$invalidItem = $validItem;
		while (in_array($invalidItem, $validItems))
		{
			$invalidItem = str_shuffle($invalidItem);
		}


		$item = new \StdClass;
		$item->validItems = $validItems;
		$item->validItem = $validItem;
		$item->invalidItem = $invalidItem;

		return $item;

	}


	/**
	 * Test the Joomla\Registry\Runtime::checkFunction method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Runtime::checkFunction
	 * @since   1.0
	 */
	public function testCheckFunction()
	{
		$item = $this->getFunctionTests();
		$this->testCheck($item->validItem, $item->invalidItem, 'checkFunction');

	}

	/**
	 * Gets some test class strings
	 *
	 * @return  \StdClass an object with the item strings
	 * @since   1.0
	 */
	private function getClassTests($type = 'internal')
	{

		$validItemsM = get_declared_classes();
		$validItems = $validItemsM[$type];
		$validItem = array_pop($validItems);
		$invalidItem = $validItem;
		while (in_array($invalidItem, $validItems))
		{
			$invalidItem = str_shuffle($invalidItem);
		}


		$item = new \StdClass;
		$item->validItems = $validItems;
		$item->validItem = $validItem;
		$item->invalidItem = $invalidItem;

		return $item;

	}


	/**
	 * Test the Joomla\Registry\Runtime::checkFunction method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Registry\Runtime::checkFunction
	 * @since   1.0
	 */
	public function testCheckClass()
	{
		$item = $this->getClassTests();
		$this->testCheck($item->validItem, $item->validItem, 'checkClass');


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
		$a = Runtime::getInstance('a');
		$b = Runtime::getInstance('a');
		$c = Runtime::getInstance('c');

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
