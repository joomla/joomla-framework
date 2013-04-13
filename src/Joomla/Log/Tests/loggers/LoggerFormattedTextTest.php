<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

require_once __DIR__ . '/stubs/formattedtext/inspector.php';

use Joomla\Log\Log;
use Joomla\Log\LogEntry;
use Joomla\Factory;

/**
 * Test class for JLogLoggerFormattedText.
 *
 * @since  1.0
 */
class JLogLoggerFormattedTextTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor01()
	{
		// Setup the basic configuration.
		$config = array(
			'text_file_path' => JPATH_TESTS . '/tmp',
			'text_file' => '',
			'text_entry_format' => null
		);
		$logger = new JLogLoggerFormattedTextInspector($config);

		// Default format string.
		$this->assertEquals($logger->format, '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals($logger->fields, array('DATETIME', 'PRIORITY', 'CATEGORY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals($logger->path, JPATH_TESTS . '/tmp/error.php', 'Line: ' . __LINE__);
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
			'text_file_path' => JPATH_TESTS . '/tmp',
			'text_file' => 'foo.log',
			'text_entry_format' => null
		);
		$logger = new JLogLoggerFormattedTextInspector($config);

		// Default format string.
		$this->assertEquals($logger->format, '{DATETIME}	{PRIORITY}	{CATEGORY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals($logger->fields, array('DATETIME', 'PRIORITY', 'CATEGORY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals($logger->path, JPATH_TESTS . '/tmp/foo.log', 'Line: ' . __LINE__);
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
			'text_file_path' => JPATH_TESTS . '/tmp',
			'text_file' => '',
			'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'
		);
		$logger = new JLogLoggerFormattedTextInspector($config);

		// Default format string.
		$this->assertEquals($logger->format, '{DATETIME}	{PRIORITY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals($logger->fields, array('DATETIME', 'PRIORITY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals($logger->path, JPATH_TESTS . '/tmp/error.php', 'Line: ' . __LINE__);
	}

	/**
	 * Test the Joomla\Log\Logger\FormattedText::__construct method.
	 *
	 * @return void
	 */
	public function testConstructor04()
	{
		// Temporarily override the config cache in JFactory.
		$temp = Factory::$config;
		Factory::$config = (object) array('log_path' => '/var/logs');

		// Setup the basic configuration.
		$config = array(
			'text_file_path' => '',
			'text_file' => '',
			'text_entry_format' => '{DATETIME}	{PRIORITY}	{MESSAGE}'
		);
		$logger = new JLogLoggerFormattedTextInspector($config);

		// Default format string.
		$this->assertEquals($logger->format, '{DATETIME}	{PRIORITY}	{MESSAGE}', 'Line: ' . __LINE__);

		// Default format string.
		$this->assertEquals($logger->fields, array('DATETIME', 'PRIORITY', 'MESSAGE'), 'Line: ' . __LINE__);

		// Default file name.
		$this->assertEquals($logger->path, '/var/logs/error.php', 'Line: ' . __LINE__);

		Factory::$config = $temp;
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
			'text_file_path' => JPATH_TESTS . '/tmp',
			'text_file' => '',
			'text_entry_format' => '{PRIORITY}	{CATEGORY}	{MESSAGE}'
		);
		$logger = new JLogLoggerFormattedTextInspector($config);

		// Remove the log file if it exists.
		@unlink($logger->path);

		$logger->addEntry(new LogEntry('Testing Entry 01'));
		$this->assertEquals(
			$this->getLastLine($logger->path),
			'INFO	-	Testing Entry 01',
			'Line: ' . __LINE__
		);

		$logger->addEntry(new LogEntry('Testing 02', Log::ERROR));
		$this->assertEquals(
			$this->getLastLine($logger->path),
			'ERROR	-	Testing 02',
			'Line: ' . __LINE__
		);

		$logger->addEntry(new LogEntry('Testing3', Log::EMERGENCY, 'deprecated'));
		$this->assertEquals(
			$this->getLastLine($logger->path),
			'EMERGENCY	deprecated	Testing3',
			'Line: ' . __LINE__
		);

		// Remove the log file if it exists.
		@unlink($logger->path);
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
