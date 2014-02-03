<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

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
	 * Provides the data to test the makeSafe method.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function dataTestStripExt()
	{
		return array(
			array(
				'foobar.php',
				'foobar',
			),
			array(
				'foobar..php',
				'foobar.',
			),
			array(
				'foobar.php.',
				'foobar.php',
			),
		);
	}

	/**
	 * Test makeSafe method
	 *
	 * @param   string  $fileName        The name of the file with extension
	 * @param   string  $nameWithoutExt  Name without extension
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::stripExt
	 * @dataProvider  dataTestStripExt
	 * @since         1.0
	 */
	public function testStripExt($fileName, $nameWithoutExt)
	{
		$this->assertEquals(
			$this->object->stripExt($fileName),
			$nameWithoutExt,
			'Line:' . __LINE__ . ' file extension should be stripped.'
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
	 * Test makeCopy method
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::copy
	 * @since         1.0
	 */
	public function testCopy()
	{
		$name = 'tempFile';
		$path = __DIR__;
		$copiedFileName = 'copiedTempFile';
		$data = 'Lorem ipsum dolor sit amet';

		// Create a temp file to test copy operation
		$this->object->write($path . '/' . $name, $data);

		// Trying to read non-existing file.
		$this->assertThat(
			File::copy($path . '/' . $name . 'foobar', $path . '/' . $copiedFileName),
			$this->isFalse(),
			'Line:' . __LINE__ . ' File should not copy successfully.'
		);
		File::delete($path . '/' . $copiedFileName);

		$this->assertThat(
			File::copy($path . '/' . $name, $path . '/' . $copiedFileName),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should copy successfully.'
		);
		File::delete($path . '/' . $copiedFileName);

		$this->assertThat(
			File::copy($name, $copiedFileName, $path),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should copy successfully.'
		);
		File::delete($path . '/' . $copiedFileName);

		// Copy using streams.
		$this->assertThat(
			File::copy($name, $copiedFileName, $path, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should copy successfully.'
		);
		File::delete($path . '/' . $copiedFileName);

		File::delete($path . '/' . $name);
	}

	/**
	 * Test delete method
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::delete
	 * @since         1.0
	 */
	public function testDelete()
	{
		$name = 'tempFile';
		$path = __DIR__;
		$data = 'Lorem ipsum dolor sit amet';

		// Create a temp file to test delete operation
		$this->object->write($path . '/' . $name, $data);

		$this->assertThat(
			File::delete($path . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be deleted successfully.'
		);
	}

	/**
	 * Test move method
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::move
	 * @since         1.0
	 */
	public function testMove()
	{
		$name = 'tempFile';
		$path = __DIR__;
		$movedFileName = 'movedTempFile';
		$data = 'Lorem ipsum dolor sit amet';

		// Create a temp file to test copy operation
		$this->object->write($path . '/' . $name, $data);

		$this->assertThat(
			File::move($path . '/' . $name, $path . '/' . $movedFileName),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be moved successfully.'
		);

		$this->assertThat(
			File::move($movedFileName, $name, $path),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be moved successfully.'
		);

		// Using streams.
		$this->assertThat(
			File::move($name, $movedFileName, $path, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be moved successfully.'
		);

		File::delete($path . '/' . $movedFileName);
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
	 * Test write method
	 *
	 * @return void
	 *
	 * @covers        Joomla\Filesystem\File::write
	 * @since         1.0
	 */
	public function testWrite()
	{
		$name = 'tempFile';
		$path = __DIR__;
		$data = 'Lorem ipsum dolor sit amet';

		// Create a file on pre existing path.
		$this->assertThat(
			File::write($path . '/' . $name, $data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be written successfully.'
		);

		// Create a file on pre existing path by using streams.
		$this->assertThat(
			File::write($path . '/' . $name, $data, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be written successfully.'
		);

		// Create a file on non-existing path.
		$this->assertThat(
			File::write($path . '/TempFolder/' . $name, $data),
			$this->isTrue(),
			'Line:' . __LINE__ . ' File should be written successfully.'
		);

		// Removes file and folder.
		File::delete($path . '/' . $name);
		Folder::delete($path . '/TempFolder');
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
