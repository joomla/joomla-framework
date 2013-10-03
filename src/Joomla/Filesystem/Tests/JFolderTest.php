<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

/**
 * Test class for JFolder.
 *
 * @since  1.0
 */
class FolderTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var Joomla\Filesystem\Folder
	 */
	protected $object;

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
	 * @todo Implement testCreate().
	 *
	 * @return void
	 */
	public function testCreate()
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
	 * Tests the Folder::delete method with an array as an input
	 *
	 * @return  void
	 *
	 * @expectedException  UnexpectedValueException
	 */
	public function testDeleteArrayPath()
	{
		Folder::delete(array('/path/to/folder'));
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
	 * Tests the Folder::files method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @covers  Joomla\Filesystem\Folder::files
	 * @covers  Joomla\Filesystem\Folder::_items
	 */
	public function testFiles()
	{
		// Make sure previous test files are cleaned up
		$this->_cleanupTestFiles();

		// Make some test files and folders
		mkdir(Path::clean(__DIR__ . '/tmp/test'), 0777, true);
		file_put_contents(Path::clean(__DIR__ . '/tmp/test/index.html'), 'test');
		file_put_contents(Path::clean(__DIR__ . '/tmp/test/index.txt'), 'test');
		mkdir(Path::clean(__DIR__ . '/tmp/test/test'), 0777, true);
		file_put_contents(Path::clean(__DIR__ . '/tmp/test/test/index.html'), 'test');
		file_put_contents(Path::clean(__DIR__ . '/tmp/test/test/index.txt'), 'test');

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'index.*', true, true, array('index.html'));
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);
		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/index.txt'),
				Path::clean(__DIR__ . '/tmp/test/test/index.txt')
			),
			$result,
			'Line: ' . __LINE__ . ' Should exclude index.html files'
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'index.html', true, true);
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);
		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/index.html'),
				Path::clean(__DIR__ . '/tmp/test/test/index.html')
			),
			$result,
			'Line: ' . __LINE__ . ' Should include full path of both index.html files'
		);

		$this->assertEquals(
			array(
				Path::clean('index.html'),
				Path::clean('index.html')
			),
			Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'index.html', true, false),
			'Line: ' . __LINE__ . ' Should include only file names of both index.html files'
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'index.html', false, true);
		$result[0] = realpath($result[0]);
		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/index.html')
			),
			$result,
			'Line: ' . __LINE__ . ' Non-recursive should only return top folder file full path'
		);

		$this->assertEquals(
			array(
				Path::clean('index.html')
			),
			Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'index.html', false, false),
			'Line: ' . __LINE__ . ' non-recursive should return only file name of top folder file'
		);

		$this->assertFalse(
			Folder::files('/this/is/not/a/path'),
			'Line: ' . __LINE__ . ' Non-existent path should return false'
		);

		$this->assertEquals(
			array(),
			Folder::files(Path::clean(__DIR__ . '/tmp/test'), 'nothing.here', true, true, array(), array()),
			'Line: ' . __LINE__ . ' When nothing matches the filter, should return empty array'
		);

		// Clean up our files
		$this->_cleanupTestFiles();
	}

	/**
	 * Tests the Folder::folders method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @covers  Joomla\Filesystem\Folder::files
	 * @covers  Joomla\Filesystem\Folder::_items
	 */
	public function testFolders()
	{
		// Make sure previous test files are cleaned up
		$this->_cleanupTestFiles();

		// Create the test folders
		mkdir(Path::clean(__DIR__ . '/tmp/test'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo1'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo1/bar1'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo1/bar2'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo2'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo2/bar1'), 0777, true);
		mkdir(Path::clean(__DIR__ . '/tmp/test/foo2/bar2'), 0777, true);

		$this->assertEquals(
			array(),
			Folder::folders(Path::clean(__DIR__ . '/tmp/test'), 'bar1', true, true, array('foo1', 'foo2'))
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::folders(Path::clean(__DIR__ . '/tmp/test'), 'bar1', true, true, array('foo1'));
		$result[0] = realpath($result[0]);
		$this->assertEquals(
			array(Path::clean(__DIR__ . '/tmp/test/foo2/bar1')),
			$result
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::folders(Path::clean(__DIR__ . '/tmp/test'), 'bar1', true, true);
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);
		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/foo1/bar1'),
				Path::clean(__DIR__ . '/tmp/test/foo2/bar1'),
			),
			$result
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::folders(Path::clean(__DIR__ . '/tmp/test'), 'bar', true, true);
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);
		$result[2] = realpath($result[2]);
		$result[3] = realpath($result[3]);
		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/foo1/bar1'),
				Path::clean(__DIR__ . '/tmp/test/foo1/bar2'),
				Path::clean(__DIR__ . '/tmp/test/foo2/bar1'),
				Path::clean(__DIR__ . '/tmp/test/foo2/bar2'),
			),
			$result
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::folders(Path::clean(__DIR__ . '/tmp/test'), '.', true, true);
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);
		$result[2] = realpath($result[2]);
		$result[3] = realpath($result[3]);
		$result[4] = realpath($result[4]);
		$result[5] = realpath($result[5]);

		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/foo1'),
				Path::clean(__DIR__ . '/tmp/test/foo1/bar1'),
				Path::clean(__DIR__ . '/tmp/test/foo1/bar2'),
				Path::clean(__DIR__ . '/tmp/test/foo2'),
				Path::clean(__DIR__ . '/tmp/test/foo2/bar1'),
				Path::clean(__DIR__ . '/tmp/test/foo2/bar2'),
			),
			$result
		);

		$this->assertEquals(
			array(
				Path::clean('bar1'),
				Path::clean('bar1'),
				Path::clean('bar2'),
				Path::clean('bar2'),
				Path::clean('foo1'),
				Path::clean('foo2'),
			),
			Folder::folders(Path::clean(__DIR__ . '/tmp/test'), '.', true, false)
		);

		// Use of realpath to ensure test works for on all platforms
		$result = Folder::folders(Path::clean(__DIR__ . '/tmp/test'), '.', false, true);
		$result[0] = realpath($result[0]);
		$result[1] = realpath($result[1]);

		$this->assertEquals(
			array(
				Path::clean(__DIR__ . '/tmp/test/foo1'),
				Path::clean(__DIR__ . '/tmp/test/foo2'),
			),
			$result
		);

		$this->assertEquals(
			array(
				Path::clean('foo1'),
				Path::clean('foo2'),
			),
			Folder::folders(Path::clean(__DIR__ . '/tmp/test'), '.', false, false, array(), array())
		);

		$this->assertFalse(
			Folder::folders('this/is/not/a/path'),
			'Line: ' . __LINE__ . ' Non-existent path should return false'
		);

		// Clean up the folders
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo2/bar2'));
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo2/bar1'));
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo2'));
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo1/bar2'));
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo1/bar1'));
		rmdir(Path::clean(__DIR__ . '/tmp/test/foo1'));
		rmdir(Path::clean(__DIR__ . '/tmp/test'));
	}

	/**
	 * Test...
	 *
	 * @todo Implement testListFolderTree().
	 *
	 * @return void
	 */
	public function testListFolderTree()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	 * Tests the Folder::makeSafe method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 *
	 * @covers  Joomla\Filesystem\Folder::makeSafe
	 */
	public function testMakeSafe()
	{
		$actual = Folder::makeSafe('test1/testdirectory');
		$this->assertEquals('test1/testdirectory', $actual);
	}

	/**
	 * Convenience method to cleanup before and after testFiles
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function _cleanupTestFiles()
	{
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test/test/index.html'));
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test/test/index.txt'));
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test/test'));
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test/index.html'));
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test/index.txt'));
		$this->_cleanupFile(Path::clean(__DIR__ . '/tmp/test'));
	}

	/**
	 * Convenience method to clean up for files test
	 *
	 * @param   string  $path  The path to clean
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	private function _cleanupFile($path)
	{
		if (file_exists($path))
		{
			if (is_file($path))
			{
				unlink($path);
			}
			elseif (is_dir($path))
			{
				rmdir($path);
			}
		}
	}
}
