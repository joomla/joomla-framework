<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive\Tests;

use Joomla\Archive\Archive as Archive;
use Joomla\Archive\Zip as ArchiveZip;
use Joomla\Archive\Tar as ArchiveTar;
use Joomla\Archive\Gzip as ArchiveGzip;
use Joomla\Archive\Bzip2 as ArchiveBzip2;
use Joomla\Factory;

/**
 * Test class for Joomla\Archive\Archive.
 *
 * @since  1.0
 */
class ArchiveTest extends \PHPUnit_Framework_TestCase
{
	protected static $outputPath;

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return mixed
	 */
	protected function setUp()
	{
		parent::setUp();

		self::$outputPath = __DIR__ . '/output';

		if (!is_dir(self::$outputPath))
		{
			mkdir(self::$outputPath, 0777);
		}
	}

	/**
	 * Tests extracting ZIP.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 */
	public function testExtractZip()
	{
		if (!is_dir(self::$outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!ArchiveZip::isSupported())
		{
			$this->markTestSkipped('ZIP files can not be extracted.');

			return;
		}

		Archive::extract(__DIR__ . '/logo.zip', self::$outputPath);
		$this->assertTrue(is_file(self::$outputPath . '/logo-zip.png'));

		if (is_file(self::$outputPath . '/logo-zip.png'))
		{
			unlink(self::$outputPath . '/logo-zip.png');
		}
	}

	/**
	 * Tests extracting TAR.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 */
	public function testExtractTar()
	{
		if (!is_dir(self::$outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!ArchiveTar::isSupported())
		{
			$this->markTestSkipped('Tar files can not be extracted.');

			return;
		}

		Archive::extract(__DIR__ . '/logo.tar', self::$outputPath);
		$this->assertTrue(is_file(self::$outputPath . '/logo-tar.png'));

		if (is_file(self::$outputPath . '/logo-tar.png'))
		{
			unlink(self::$outputPath . '/logo-tar.png');
		}
	}

	/**
	 * Tests extracting gzip.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 */
	public function testExtractGzip()
	{
		if (!is_dir(self::$outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!is_writable(self::$outputPath) || !is_writable(Factory::getConfig()->get('tmp_path')))
		{
			$this->markTestSkipped("Folder not writable.");

			return;
		}

		if (!ArchiveGzip::isSupported())
		{
			$this->markTestSkipped('Gzip files can not be extracted.');

			return;
		}

		Archive::extract(__DIR__ . '/logo.gz', self::$outputPath . '/logo-gz.png');
		$this->assertTrue(is_file(self::$outputPath . '/logo-gz.png'));

		if (is_file(self::$outputPath . '/logo-gz.png'))
		{
			unlink(self::$outputPath . '/logo-gz.png');
		}
	}

	/**
	 * Tests extracting bzip2.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 */
	public function testExtractBzip2()
	{
		if (!is_dir(self::$outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!is_writable(self::$outputPath) || !is_writable(Factory::getConfig()->get('tmp_path')))
		{
			$this->markTestSkipped("Folder not writable.");

			return;
		}

		if (!ArchiveBzip2::isSupported())
		{
			$this->markTestSkipped('Bzip2 files can not be extracted.');

			return;
		}

		Archive::extract(__DIR__ . '/logo.bz2', self::$outputPath . '/logo-bz2.png');
		$this->assertTrue(is_file(self::$outputPath . '/logo-bz2.png'));

		if (is_file(self::$outputPath . '/logo-bz2.png'))
		{
			unlink(self::$outputPath . '/logo-bz2.png');
		}
	}

	/**
	 * Test...
	 *
	 * @return  mixed
	 *
	 * @covers  Joomla\Archive\Archive::getAdapter
	 */
	public function testGetAdapter()
	{
		$zip = Archive::getAdapter('zip');
		$this->assertInstanceOf('Joomla\\Archive\\Zip', $zip);
		$bzip2 = Archive::getAdapter('bzip2');
		$this->assertInstanceOf('Joomla\\Archive\\Bzip2', $bzip2);
		$gzip = Archive::getAdapter('gzip');
		$this->assertInstanceOf('Joomla\\Archive\\Gzip', $gzip);
		$tar = Archive::getAdapter('tar');
		$this->assertInstanceOf('Joomla\\Archive\\Tar', $tar);
	}

	/**
	 * Test...
	 *
	 * @return  mixed
	 *
	 * @covers             Joomla\Archive\Archive::getAdapter
	 * @expectedException  UnexpectedValueException
	 */
	public function testGetAdapterException()
	{
		$zip = Archive::getAdapter('unknown');
	}
}
