<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Log\Tests\Logger;

use Joomla\Log\Log;
use Joomla\Log\LogEntry;
use Joomla\Log\Logger\Formattedtext;
use Joomla\Test\TestHelper;

/**
 * Test class for Joomla\Log\Logger\Formattedtext.
 *
 * @since  1.0
 */
class FormattedtextTest extends \PHPUnit_Framework_TestCase
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
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor01()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => __DIR__ . '/tmp',
			'text_file' => '',
			'text_entry_format' => null
		);

		$logger = new Formattedtext($config);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'format'), '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'fields'), array('DATETIME', 'PRIORITY', 'CATEGORY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals(TestHelper::getValue($logger, 'path'), __DIR__ . '/tmp/error.php', 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor02()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => __DIR__ . '/tmp',
			'text_file' => 'foo.log',
			'text_entry_format' => null
		);
		$logger = new Formattedtext($config);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'format'), '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'fields'), array('DATETIME', 'PRIORITY', 'CATEGORY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals(TestHelper::getValue($logger, 'path'), __DIR__ . '/tmp/foo.log', 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor03()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => __DIR__ . '/tmp',
			'text_file' => '',
			'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'
		);
		$logger = new Formattedtext($config);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'format'), '{DATETIME}	{PRIORITY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'fields'), array('DATETIME', 'PRIORITY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals(TestHelper::getValue($logger, 'path'), __DIR__ . '/tmp/error.php', 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor04()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => '/var/logs',
			'text_file' => '',
			'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'
		);
		$logger = new Formattedtext($config);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'format'), '{DATETIME}	{PRIORITY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals(TestHelper::getValue($logger, 'fields'), array('DATETIME', 'PRIORITY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals(TestHelper::getValue($logger, 'path'), '/var/logs/error.php', 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\FormattedText::addEntry method.
	 *
	 * @return void
	 */
	public function testAddEntry()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => __DIR__ . '/tmp',
			'text_file' => '',
			'text_entry_format' => '{PRIORITY}	{CATEGORY}	{MESSAGE}'
		);
		$logger = new Formattedtext($config);

		// Remove the log file if it exists.
		@unlink(TestHelper::getValue($logger, 'path'));

		$logger->addEntry(new LogEntry('Testing Entry 01'));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'INFO	-	Testing Entry 01',
			'Line: ' . __LINE__
		);

		$logger->addEntry(new LogEntry('Testing 02', Log::ERROR));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'ERROR	-	Testing 02',
			'Line: ' . __LINE__
		);

		$logger->addEntry(new LogEntry('Testing3', Log::EMERGENCY, 'deprecated'));
		$this->assertEquals(
			$this->getLastLine(TestHelper::getValue($logger, 'path')),
			'EMERGENCY	deprecated	Testing3',
			'Line: ' . __LINE__
		);

		// Remove the log file if it exists.
		@unlink(TestHelper::getValue($logger, 'path'));
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
