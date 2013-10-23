<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests\Logger;

use Joomla\Log\Log;
use Joomla\Log\LogEntry;
use Joomla\Log\Logger\W3c;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Log\Logger\W3c.
 *
 * @since  1.0
 */
class W3cTest extends \PHPUnit_Framework_TestCase
{
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

		// Create tmp directory for tests.
		if (!is_dir(__DIR__ . '/tmp'))
		{
			mkdir(__DIR__ . '/tmp');
		}
	}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function tearDown()
	{
		// Remove tmp directory.
		@rmdir(__DIR__ . '/tmp');

		parent::tearDown();
	}

	/**
	 * Test the Joomla\Log\Logger\W3c::addEntry method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testAddEntry()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => __DIR__ . '/tmp',
		);
		$logger = new W3c($config);

		// Remove the log file if it exists.
		@ unlink(TestHelper::getValue($logger, 'path'));

		$logger->addEntry(new LogEntry('Testing Entry 01', Log::INFO, null, '1980-04-18'));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'1980-04-18	00:00:00	INFO	-	-	Testing Entry 01',
			'Line: ' . __LINE__
		);

		$_SERVER['REMOTE_ADDR'] = '192.168.0.1';

		$logger->addEntry(new LogEntry('Testing 02', Log::ERROR, null, '1982-12-15'));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'1982-12-15	00:00:00	ERROR	192.168.0.1	-	Testing 02',
			'Line: ' . __LINE__
		);

		$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

		$logger->addEntry(new LogEntry('Testing3', Log::EMERGENCY, 'deprecated', '1980-04-18'));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'1980-04-18	00:00:00	EMERGENCY	127.0.0.1	deprecated	Testing3',
			'Line: ' . __LINE__
		);

		// Remove the log file if it exists.
		@ unlink(TestHelper::getValue($logger, 'path'));
	}

	/**
	 * Method to get the last line of a file.  This is fairly safe for very large files.
	 *
	 * @param   string  $path  The path to the file for which to get the last line.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	protected function getLastLine($path)
	{
		$cursor = -1;
		$line = '';

		// Open the file up to the last character.
		$f = fopen($path, 'r');
		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);

		// Trim trailing newline characters.
		while ($char === "\n" || $char === "\r")
		{
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}

		// Read until the start of the file or first newline character.
		while ($char !== false && $char !== "\n" && $char !== "\r")
		{
			$line = $char . $line;
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}

		// Close the file.
		fclose($f);

		return $line;
	}
}
