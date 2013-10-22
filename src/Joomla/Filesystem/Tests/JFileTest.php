<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\File;

/**
 * Test class for Joomla\Filesystem\File.
 *
 * @since  1.0
 */
class JFileTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Joomla\Filesystem\File
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new File;
	}

	/**
	 * Test...
	 *
	 * @todo Implement testStripExt().
	 *
	 * @return void
	 */
	public function testStripExt()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Provides the data to test the makeSafe method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function dataTestMakeSafe()
	{
		return array(
			array(
				'joomla.',
				array('#^\.#'),
				'joomla',
				'There should be no fullstop on the end of a filename',
			),
			array(
				'Test j00mla_5-1.html',
				array('#^\.#'),
				'Test j00mla_5-1.html',
				'Alphanumeric symbols, dots, dashes, spaces and underscores should not be filtered',
			),
			array(
				'Test j00mla_5-1.html',
				array('#^\.#', '/\s+/'),
				'Testj00mla_5-1.html',
				'Using strip chars parameter here to strip all spaces',
			),
			array(
				'joomla.php!.',
				array('#^\.#'),
				'joomla.php',
				'Non-alphanumeric symbols should be filtered to avoid disguising file extensions',
			),
			array(
				'joomla.php.!',
				array('#^\.#'),
				'joomla.php',
				'Non-alphanumeric symbols should be filtered to avoid disguising file extensions',
			),
			array(
				'.gitignore',
				array(),
				'.gitignore',
				'Files starting with a fullstop should be allowed when strip chars parameter is empty',
			),
		);
	}

	/**
	 * Test makeSafe method
	 *
	 * @param   string  $name        The name of the file to test filtering of
	 * @param   array   $stripChars  Whether to filter spaces out the name or not
	 * @param   string  $expected    The expected safe file name
	 * @param   string  $message     The message to show on failure of test
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::makeSafe
	 * @dataProvider  dataTestMakeSafe
	 * @since         1.0
	 */
	public function testMakeSafe($name, $stripChars, $expected, $message)
	{
		$this->assertEquals($this->object->makeSafe($name, $stripChars), $expected, $message);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testCopy().
	 *
	 * @return void
	 */
	public function testCopy()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testDelete().
	 *
	 * @return void
	 */
	public function testDelete()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testMove().
	 *
	 * @return void
	 */
	public function testMove()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testRead().
	 *
	 * @return void
	 */
	public function testRead()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testWrite().
	 *
	 * @return void
	 */
	public function testWrite()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement testUpload().
	 *
	 * @return void
	 */
	public function testUpload()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
