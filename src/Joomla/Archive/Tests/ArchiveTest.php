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

/**
 * Test class for Joomla\Archive\Archive.
 *
 * @since  1.0
 */
class ArchiveTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Object under test
	 *
	 * @var    Archive
	 * @since  1.0
	 */
	protected $fixture;

	/**
	 * Output directory
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $outputPath;

	/**
	 * Sets up the fixture.
	 *
	 * This method is called before a test is executed.
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->outputPath = __DIR__ . '/output';

		if (!is_dir($this->outputPath))
		{
			mkdir($this->outputPath, 0777);
		}

		$this->fixture = new Archive;
	}

	/**
	 * Tests extracting ZIP.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 * @since   1.0
	 */
	public function testExtractZip()
	{
		if (!is_dir($this->outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!ArchiveZip::isSupported())
		{
			$this->markTestSkipped('ZIP files can not be extracted.');

			return;
		}

		$this->fixture->extract(__DIR__ . '/logo.zip', $this->outputPath);
		$this->assertTrue(is_file($this->outputPath . '/logo-zip.png'));

		if (is_file($this->outputPath . '/logo-zip.png'))
		{
			unlink($this->outputPath . '/logo-zip.png');
		}
	}

	/**
	 * Tests extracting TAR.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 * @since   1.0
	 */
	public function testExtractTar()
	{
		if (!is_dir($this->outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!ArchiveTar::isSupported())
		{
			$this->markTestSkipped('Tar files can not be extracted.');

			return;
		}

		$this->fixture->extract(__DIR__ . '/logo.tar', $this->outputPath);
		$this->assertTrue(is_file($this->outputPath . '/logo-tar.png'));

		if (is_file($this->outputPath . '/logo-tar.png'))
		{
			unlink($this->outputPath . '/logo-tar.png');
		}
	}

	/**
	 * Tests extracting gzip.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 * @since   1.0
	 */
	public function testExtractGzip()
	{
		if (!is_dir($this->outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!is_writable($this->outputPath) || !is_writable($this->fixture->options['tmp_path']))
		{
			$this->markTestSkipped("Folder not writable.");

			return;
		}

		if (!ArchiveGzip::isSupported())
		{
			$this->markTestSkipped('Gzip files can not be extracted.');

			return;
		}

		$this->fixture->extract(__DIR__ . '/logo.gz', $this->outputPath . '/logo-gz.png');
		$this->assertTrue(is_file($this->outputPath . '/logo-gz.png'));

		if (is_file($this->outputPath . '/logo-gz.png'))
		{
			unlink($this->outputPath . '/logo-gz.png');
		}
	}

	/**
	 * Tests extracting bzip2.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Archive::extract
	 * @since   1.0
	 */
	public function testExtractBzip2()
	{
		if (!is_dir($this->outputPath))
		{
			$this->markTestSkipped("Couldn't create folder.");

			return;
		}

		if (!is_writable($this->outputPath) || !is_writable($this->fixture->options['tmp_path']))
		{
			$this->markTestSkipped("Folder not writable.");

			return;
		}

		if (!ArchiveBzip2::isSupported())
		{
			$this->markTestSkipped('Bzip2 files can not be extracted.');

			return;
		}

		$this->fixture->extract(__DIR__ . '/logo.bz2', $this->outputPath . '/logo-bz2.png');
		$this->assertTrue(is_file($this->outputPath . '/logo-bz2.png'));

		if (is_file($this->outputPath . '/logo-bz2.png'))
		{
			unlink($this->outputPath . '/logo-bz2.png');
		}
	}

	/**
	 * Test...
	 *
	 * @return  mixed
	 *
	 * @covers  Joomla\Archive\Archive::getAdapter
	 * @since   1.0
	 */
	public function testGetAdapter()
	{
		$zip = $this->fixture->getAdapter('zip');
		$this->assertInstanceOf('Joomla\\Archive\\Zip', $zip);
		$bzip2 = $this->fixture->getAdapter('bzip2');
		$this->assertInstanceOf('Joomla\\Archive\\Bzip2', $bzip2);
		$gzip = $this->fixture->getAdapter('gzip');
		$this->assertInstanceOf('Joomla\\Archive\\Gzip', $gzip);
		$tar = $this->fixture->getAdapter('tar');
		$this->assertInstanceOf('Joomla\\Archive\\Tar', $tar);
	}

	/**
	 * Test getAdapter exception.
	 *
	 * @return  void
	 *
	 * @covers             Joomla\Archive\Archive::getAdapter
	 * @expectedException  \InvalidArgumentException
	 * @since              1.0
	 */
	public function testGetAdapterException()
	{
		$this->fixture->getAdapter('unknown');
	}

	/**
	 * Test getAdapter exception message.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testGetAdapterExceptionMessage()
	{
		try
		{
			$this->fixture->getAdapter('unknown');
		}

		catch (\InvalidArgumentException $e)
		{
			$this->assertEquals(
				'Archive adapter "unknown" (class "Joomla\\Archive\\Unknown") not found or supported.',
				$e->getMessage()
			);
		}
	}

	/**
	 * Test setAdapter exception.
	 *
	 * @return  void
	 *
	 * @covers             Joomla\Archive\Archive::setAdapter
	 * @expectedException  \InvalidArgumentException
	 * @since              1.0
	 */
	public function testSetAdapterException()
	{
		$this->fixture->setAdapter('unknown', 'unknown-class');
	}

	/**
	 * Test setAdapter exception message.
	 *
	 * @return  void
	 *
	 * @since  1.0
	 */
	public function testSetAdapterExceptionMessage()
	{
		try
		{
			$this->fixture->setAdapter('unknown', 'FooArchiveAdapter');
		}

		catch (\InvalidArgumentException $e)
		{
			$this->assertEquals(
				'The provided adapter "unknown" (class "FooArchiveAdapter") must implement Joomla\\Archive\\ExtractableInterface',
				$e->getMessage()
			);
		}
	}
}
