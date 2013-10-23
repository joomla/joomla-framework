<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests\Logger;

use Joomla\Log\Log;
use Joomla\Log\LogEntry;
use Joomla\Log\Logger\Echoo as LoggerEcho;

/**
 * Test class for Joomla\Log\Logger\Echoo.
 *
 * @since  1.0
 */
class EchoTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    LoggerEcho
	 * @since  1.0
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		// Create bogus config.
		$config = array();

		// Get an instance of the logger.
		$this->object = new LoggerEcho($config);
	}

	/**
	 * Test the Joomla\Log\Logger\Echoo::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry01()
	{
		$this->expectOutputString("DEBUG: TESTING [deprecated]\n");
		$this->object->addEntry(new LogEntry('TESTING', Log::DEBUG, 'DePrEcAtEd'));
	}

	/**
	 * Test the Joomla\Log\Logger\Echoo::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry02()
	{
		$this->expectOutputString("CRITICAL: TESTING2 [bam]\n");
		$this->object->addEntry(new LogEntry('TESTING2', Log::CRITICAL, 'BAM'));
	}

	/**
	 * Test the Joomla\Log\Logger\Echoo::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry03()
	{
		$this->expectOutputString("ERROR: Testing3\n");
		$this->object->addEntry(new LogEntry('Testing3', Log::ERROR));
	}

	/**
	 * Test the Joomla\Log\Logger\Echoo::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry04()
	{
		$this->expectOutputString("INFO: Testing 4\n");
		$this->object->addEntry(new LogEntry('Testing 4'));
	}
}
