<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Filesystem\Folder;
use Joomla\Filesystem\File;
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
	 * Tests the Folder::copy method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCopy()
	{
		$name = 'tempFolder';
		$copiedFolderName = 'tempCopiedFolderName';
		$path = __DIR__;

		Folder::create($path . '/' . $name);

		$this->assertThat(
			Folder::copy($name, $copiedFolderName, $path),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be copied successfully.'
		);
		Folder::delete($path . '/' . $copiedFolderName);

		$this->assertThat(
			Folder::copy($path . '/' . $name, $path . '/' . $copiedFolderName),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be copied successfully.'
		);
		Folder::delete($path . '/' . $copiedFolderName);

		Folder::delete($path . '/' . $name);
	}

	/**
	 * Test the Folder::copy method where source folder doesn't exist.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function testCopySrcDontExist()
	{
		$name = 'tempFolder';
		$copiedFolderName = 'tempCopiedFolderName';
		$path = __DIR__;

		Folder::create($path . '/' . $name);

		try
		{
			Folder::copy($path . '/' . $name . 'foobar', $path . '/' . $copiedFolderName);
		}
		catch (Exception $exception)
		{
			// Source folder doesn't exist.
			$this->assertInstanceOf(
				'RuntimeException',
				$exception,
				'Line:' . __LINE__ . ' Folder should not be copied successfully.'
			);
		}

		Folder::delete($path . '/' . $copiedFolderName);
		Folder::delete($path . '/' . $name);
	}

	/**
	 * Test the Folder::copy method where destination folder exist already.
	 *
	 * @return void
	 *
	 * @since   1.0
	 */
	public function testCopyDestExist()
	{
		$name = 'tempFolder';
		$copiedFolderName = 'tempCopiedFolderName';
		$path = __DIR__;

		Folder::create($path . '/' . $name);
		Folder::create($path . '/' . $copiedFolderName);

		// Destination folder exist already and copy is forced.
		$this->assertThat(
			Folder::copy($name, $copiedFolderName, $path, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be copied successfully.'
		);

		try
		{
			Folder::copy($name, $copiedFolderName, $path);
		}
		catch (Exception $exception)
		{
			// Destination folder exist already and copy is not forced.
			$this->assertInstanceOf(
				'RuntimeException',
				$exception,
				'Line:' . __LINE__ . ' Folder should not be copied successfully.'
			);
		}

		Folder::delete($path . '/' . $copiedFolderName);

		Folder::delete($path . '/' . $name);
	}

	/**
	 * Tests the Folder::create method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testCreate()
	{
		$name = 'tempFolder';
		$path = __DIR__;

		$this->assertThat(
			Folder::create($path . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be created successfully.'
		);

		// Already existing directory (made by previous call).
		$this->assertThat(
			Folder::create($path . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be created successfully.'
		);

		Folder::delete($path . '/' . $name);

		// Creating parent directory recursively.
		$this->assertThat(
			Folder::create($path . '/' . $name . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be created successfully.'
		);

		Folder::delete($path . '/' . $name . '/' . $name);

		// Checking for infinite loop in the path.
		$path = __DIR__ . '/a/b/c/d/e/f/g/h/i/j/k/l/m/n/o/p/q/r/s/t/u/v/w/x/y/z';
		$this->assertThat(
			Folder::create($path . '/' . $name),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Folder should be created successfully.'
		);

		Folder::delete($path . '/' . $name);
	}

	/**
	 * Tests the Folder::delete method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDelete()
	{
		$name = 'tempFolder';
		$path = __DIR__;

		Folder::create($path . '/' . $name);

		$this->assertThat(
			Folder::delete($path . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be deleted successfully.'
		);

		// Create a folder and a sub-folder and file in it.
		$data = 'Lorem ipsum dolor sit amet';
		Folder::create($path . '/' . $name);
		File::write($path . '/' . $name . '/' . $name . '.txt', $data);
		Folder::create($path . '/' . $name . '/' . $name);

		$this->assertThat(
			Folder::delete($path . '/' . $name),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder and its sub folder & files should be deleted successfully.'
		);

		// Testing empty path.
		$this->assertThat(
			Folder::delete(''),
			$this->isFalse(),
			'Line:' . __LINE__ . ' Base folder should not be deleted successfully.'
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
	 * Tests the Folder::move method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testMove()
	{
		$name = 'tempFolder';
		$movedFolderName = 'tempMovedFolderName';
		$path = __DIR__;

		Folder::create($path . '/' . $name);

		$this->assertThat(
			Folder::move($name, $movedFolderName, $path),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be moved successfully.'
		);

		// Testing using streams.
		$this->assertThat(
			Folder::move($movedFolderName, $name, $path, true),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be moved successfully.'
		);

		$this->assertThat(
			Folder::move($path . '/' . $name, $path . '/' . $movedFolderName),
			$this->isTrue(),
			'Line:' . __LINE__ . ' Folder should be moved successfully.'
		);

		// Testing condition of source folder don't exist.
		$this->assertEquals(
			Folder::move($name, $movedFolderName, $path),
			'Cannot find source folder',
			'Line:' . __LINE__ . ' Folder should not be moved successfully.'
		);

		// Testing condition of dest folder exist already.
		$this->assertEquals(
			Folder::move($movedFolderName, $movedFolderName, $path),
			'Folder already exists',
			'Line:' . __LINE__ . ' Folder should not be moved successfully.'
		);

		Folder::delete($path . '/' . $movedFolderName);
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
	 * Tests the Folder::listFolderTree method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testListFolderTree()
	{
		$name = 'tempFolder';
		$path = __DIR__;

		// -tempFolder
		Folder::create("$path/$name");
		$this->assertEquals(
			Folder::listFolderTree("$path/$name", '.'),
			array(),
			'Line: ' . __LINE__ . ' Observed folder tree is not correct.');

		// -tempFolder
		// ---SubFolder
		$subfullname = "$path/$name/SubFolder";
		$subrelname = str_replace(JPATH_ROOT, '', $subfullname);
		Folder::create($subfullname);
		$this->assertEquals(
			Folder::listFolderTree("$path/$name", '.'),
			array(
				array(
					'id' => 1,
					'parent' => 0,
					'name' => 'SubFolder',
					'fullname' => $subfullname,
					'relname' => $subrelname
				)
			),
			'Line: ' . __LINE__ . ' Observed folder tree is not correct.');

		/* -tempFolder
			---SubFolder
			---AnotherSubFolder
		*/
		$anothersubfullname = "$path/$name/AnotherSubFolder";
		$anothersubrelname = str_replace(JPATH_ROOT, '', $anothersubfullname);
		Folder::create($anothersubfullname);
		$this->assertEquals(
			Folder::listFolderTree("$path/$name", '.'),
			array(
				array(
					'id' => 1,
					'parent' => 0,
					'name' => 'AnotherSubFolder',
					'fullname' => $anothersubfullname,
					'relname' => $anothersubrelname
				),
				array(
					'id' => 2,
					'parent' => 0,
					'name' => 'SubFolder',
					'fullname' => $subfullname,
					'relname' => $subrelname
				)

			),
			'Line: ' . __LINE__ . ' Observed folder tree is not correct.');

		/* -tempFolder
				-SubFolder
					-SubSubFolder
				-AnotherSubFolder
		*/
		$subsubfullname = "$subfullname/SubSubFolder";
		$subsubrelname = str_replace(JPATH_ROOT, '', $subsubfullname);
		Folder::create($subsubfullname);
		$this->assertEquals(
			Folder::listFolderTree("$path/$name", '.'),
			array(
				array(
					'id' => 1,
					'parent' => 0,
					'name' => 'AnotherSubFolder',
					'fullname' => $anothersubfullname,
					'relname' => $anothersubrelname
				),
				array(
					'id' => 2,
					'parent' => 0,
					'name' => 'SubFolder',
					'fullname' => $subfullname,
					'relname' => $subrelname
				),
				array(
					'id' => 3,
					'parent' => 2,
					'name' => 'SubSubFolder',
					'fullname' => $subsubfullname,
					'relname' => $subsubrelname
				)

			),
			'Line: ' . __LINE__ . ' Observed folder tree is not correct.');

		Folder::delete($path . '/' . $name);
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
