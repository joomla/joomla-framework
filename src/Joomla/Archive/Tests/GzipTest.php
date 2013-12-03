<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Archive\Tests;

use Joomla\Archive\Gzip as ArchiveGzip;

/**
 * Test class for Joomla\Archive\Gzip.
 *
 * @since  1.0
 */
class GzipTest extends \PHPUnit_Framework_TestCase
{
	protected static $outputPath;

	/**
	 * @var Joomla\Archive\Gzip
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

		$this->object = new ArchiveGzip;
	}

	/**
	 * Tests the extract Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Gzip::extract
	 */
	public function testExtract()
	{
		if (!ArchiveGzip::isSupported())
		{
			$this->markTestSkipped('Gzip files can not be extracted.');

			return;
		}

		$this->object->extract(__DIR__ . '/logo.gz', self::$outputPath . '/logo-gz.png');
		$this->assertTrue(is_file(self::$outputPath . '/logo-gz.png'));

		if (is_file(self::$outputPath . '/logo-gz.png'))
		{
			unlink(self::$outputPath . '/logo-gz.png');
		}
	}

	/**
	 * Tests the extract Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Gzip::extract
	 * @covers  Joomla\Archive\Gzip::getFilePosition
	 */
	public function testExtractWithStreams()
	{
		if (!ArchiveGzip::isSupported())
		{
			$this->markTestSkipped('Gzip files can not be extracted.');

			return;
		}

		try
		{
			$this->object->extract(__DIR__ . '/logo.gz', self::$outputPath . '/logo-gz.png', array('use_streams' => true));
		}
		catch (\RuntimeException $e)
		{
			$this->assertTrue(is_file(self::$outputPath . '/logo-gz.png'));
		}

		$this->assertTrue(is_file(self::$outputPath . '/logo-gz.png'));

		if (is_file(self::$outputPath . '/logo-gz.png'))
		{
			unlink(self::$outputPath . '/logo-gz.png');
		}
	}

	/**
	 * Tests the isSupported Method.
	 *
	 * @group   JArchive
	 * @return  void
	 *
	 * @covers  Joomla\Archive\Gzip::isSupported
	 */
	public function testIsSupported()
	{
		$this->assertEquals(
			extension_loaded('zlib'),
			ArchiveGzip::isSupported()
		);
	}

	/**
	 * Test...
	 *
	 * @todo Implement test_getFilePosition().
	 *
	 * @return void
	 */
	public function test_getFilePosition()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}
}
