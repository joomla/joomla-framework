<?php
/**
 * @package    Joomla.Test
 *
 * @copyright  Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Abstract test case class for unit testing.
 *
 * @package  Joomla.Test
 * @since    12.1
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
	/**
	 * @var    array  Various JFactory static instances stashed away to be restored later.
	 * @since  12.1
	 */
	private $_stashedFactoryState = array(
		'application' => null,
		'config' => null,
		'dates' => null,
		'database' => null,
		'session' => null,
		'language' => null
	);

	/**
	 * Assigns mock callbacks to methods.
	 *
	 * @param   object  $mockObject  The mock object that the callbacks are being assigned to.
	 * @param   array   $array       An array of methods names to mock with callbacks.
	 * This method assumes that the mock callback is named {mock}{method name}.
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function assignMockCallbacks($mockObject, $array)
	{
		foreach ($array as $index => $method)
		{
			if (is_array($method))
			{
				$methodName = $index;
				$callback = $method;
			}
			else
			{
				$methodName = $method;
				$callback = array(get_called_class(), 'mock' . $method);
			}

			$mockObject->expects($this->any())
			->method($methodName)
			->will($this->returnCallback($callback));
		}
	}

	/**
	 * Assigns mock values to methods.
	 *
	 * @param   object  $mockObject  The mock object.
	 * @param   array   $array       An associative array of methods to mock with return values:<br />
	 * string (method name) => mixed (return value)
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	public function assignMockReturns($mockObject, $array)
	{
		foreach ($array as $method => $return)
		{
			$mockObject->expects($this->any())
			->method($method)
			->will($this->returnValue($return));
		}
	}

	/**
	 * Gets a mock application object.
	 *
	 * @return  JApplication
	 *
	 * @since   12.1
	 */
	public function getMockApplication()
	{
		// Attempt to load the real class first.
		class_exists('JApplication');

		return TestMockApplication::create($this);
	}

	/**
	 * Gets a mock configuration object.
	 *
	 * @return  JConfig
	 *
	 * @since   12.1
	 */
	public function getMockConfig()
	{
		return TestMockConfig::create($this);
	}

	/**
	 * Gets a mock database object.
	 *
	 * @return  JDatabase
	 *
	 * @since   12.1
	 */
	public function getMockDatabase()
	{
		// Attempt to load the real class first.
		class_exists('JDatabaseDriver');

		return TestMockDatabaseDriver::create($this);
	}

	/**
	 * Gets a mock dispatcher object.
	 *
	 * @param   boolean  $defaults  Add default register and trigger methods for testing.
	 *
	 * @return  JEventDispatcher
	 *
	 * @since   12.1
	 */
	public function getMockDispatcher($defaults = true)
	{
		// Attempt to load the real class first.
		class_exists('JEventDispatcher');

		return TestMockDispatcher::create($this, $defaults);
	}

	/**
	 * Gets a mock document object.
	 *
	 * @return  JDocument
	 *
	 * @since   12.1
	 */
	public function getMockDocument()
	{
		// Attempt to load the real class first.
		class_exists('JDocument');

		return TestMockDocument::create($this);
	}

	/**
	 * Gets a mock language object.
	 *
	 * @return  JLanguage
	 *
	 * @since   12.1
	 */
	public function getMockLanguage()
	{
		// Attempt to load the real class first.
		class_exists('JLanguage');

		return TestMockLanguage::create($this);
	}

	/**
	 * Gets a mock session object.
	 *
	 * @param   array  $options  An array of key-value options for the JSession mock.
	 * getId : the value to be returned by the mock getId method
	 * get.user.id : the value to assign to the user object id returned by get('user')
	 * get.user.name : the value to assign to the user object name returned by get('user')
	 * get.user.username : the value to assign to the user object username returned by get('user')
	 *
	 * @return  JSession
	 *
	 * @since   12.1
	 */
	public function getMockSession($options = array())
	{
		// Attempt to load the real class first.
		class_exists('JSession');

		return TestMockSession::create($this, $options);
	}

	/**
	 * Gets a mock web object.
	 *
	 * @param   array  $options  A set of options to configure the mock.
	 *
	 * @return  JApplicationWeb
	 *
	 * @since   12.1
	 */
	public function getMockWeb($options = array())
	{
		// Attempt to load the real class first.
		class_exists('JApplicationWeb');

		return TestMockApplicationWeb::create($this, $options);
	}

	/**
	 * Sets the Factory pointers
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function restoreFactoryState()
	{
		JFactory::$application = $this->_stashedFactoryState['application'];
		JFactory::$config = $this->_stashedFactoryState['config'];
		JFactory::$dates = $this->_stashedFactoryState['dates'];
		JFactory::$session = $this->_stashedFactoryState['session'];
		JFactory::$language = $this->_stashedFactoryState['language'];
		JFactory::$database = $this->_stashedFactoryState['database'];
	}

	/**
	 * Saves the Factory pointers
	 *
	 * @return  void
	 *
	 * @since   12.1
	 */
	protected function saveFactoryState()
	{
		$this->_stashedFactoryState['application'] = JFactory::$application;
		$this->_stashedFactoryState['config'] = JFactory::$config;
		$this->_stashedFactoryState['dates'] = JFactory::$dates;
		$this->_stashedFactoryState['session'] = JFactory::$session;
		$this->_stashedFactoryState['language'] = JFactory::$language;
		$this->_stashedFactoryState['database'] = JFactory::$database;
	}

	/**
	 * Overrides the parent setup method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::setUp()
	 * @since   11.1
	 */
	protected function setUp()
	{
		parent::setUp();
	}

	/**
	 * Overrides the parent tearDown method.
	 *
	 * @return  void
	 *
	 * @see     PHPUnit_Framework_TestCase::tearDown()
	 * @since   11.1
	 */
	protected function tearDown()
	{
		parent::tearDown();
	}
}
