<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive\Tests;

use Joomla\Archive\Zip as ArchiveZip;

require_once __DIR__ . '/ZipInspector.php';

/**
 * Test class for Joomla\Archive\Zip.
 *
 * @since  1.0
 */
class ZipTest extends \PHPUnit_Framework_TestCase
{
	protected static $outputPath;

	/**
	 * @var Joomla\Archive\Zip
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

		self::$outputPath = __DIR__ . '/output';

		if (!is_dir(self::$outputPath))
		{
			mkdir(self::$outputPath, 0777);
		}

		$this->object = new ZipInspector;
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
	 * Tests the extractNative Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Zip::extractNative
	 */
	public function testExtractNative()
	{
		if (!ArchiveZip::hasNativeSupport())
		{
			$this->markTestSkipped(
				'ZIP files can not be extracted nativly.'
			);

			return;
		}

		$this->object->accessExtractNative(__DIR__ . '/logo.zip', self::$outputPath);
		$this->assertTrue(is_file(self::$outputPath . '/logo-zip.png'));

		if (is_file(self::$outputPath . '/logo-zip.png'))
		{
			unlink(self::$outputPath . '/logo-zip.png');
		}
	}

	/**
	 * Tests the extractCustom Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Zip::extractCustom
	 * @covers  Joomla\Archive\Zip::readZipInfo
	 * @covers  Joomla\Archive\Zip::getFileData
	 */
	public function testExtractCustom()
	{
		if (!ArchiveZip::isSupported())
		{
			$this->markTestSkipped(
				'ZIP files can not be extracted.'
			);

			return;
		}

		$this->object->accessExtractCustom(__DIR__ . '/logo.zip', self::$outputPath);
		$this->assertTrue(is_file(self::$outputPath . '/logo-zip.png'));

		if (is_file(self::$outputPath . '/logo-zip.png'))
		{
			unlink(self::$outputPath . '/logo-zip.png');
		}
	}

	/**
	 * Tests the extract Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Zip::extract
	 */
	public function testExtract()
	{
		if (!ArchiveZip::isSupported())
		{
			$this->markTestSkipped(
				'ZIP files can not be extracted.'
			);

			return;
		}

		$this->object->extract(__DIR__ . '/logo.zip', self::$outputPath);
		$this->assertTrue(is_file(self::$outputPath . '/logo-zip.png'));

		if (is_file(self::$outputPath . '/logo-zip.png'))
		{
			unlink(self::$outputPath . '/logo-zip.png');
		}
	}

	/**
	 * Tests the hasNativeSupport Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Zip::hasNativeSupport
	 */
	public function testHasNativeSupport()
	{
		$this->assertEquals(
			(function_exists('zip_open') && function_exists('zip_read')),
			ArchiveZip::hasNativeSupport()
		);
	}

	/**
	 * Tests the isSupported Method.
	 *
	 * @group    JArchive
	 * @return   void
	 *
	 * @covers   Joomla\Archive\Zip::isSupported
	 * @depends  testHasNativeSupport
	 */
	public function testIsSupported()
	{
		$this->assertEquals(
			(ArchiveZip::hasNativeSupport() || extension_loaded('zlib')),
			ArchiveZip::isSupported()
		);
	}

	/**
	 * Test...
	 *
	 * @covers  Joomla\Archive\Zip::checkZipData
	 *
	 * @return void
	 */
	public function testCheckZipData()
	{
		$dataZip = file_get_contents(__DIR__ . '/logo.zip');
		$this->assertTrue(
			$this->object->checkZipData($dataZip)
		);

		$dataTar = file_get_contents(__DIR__ . '/logo.tar');
		$this->assertFalse(
			$this->object->checkZipData($dataTar)
		);
	}
}
