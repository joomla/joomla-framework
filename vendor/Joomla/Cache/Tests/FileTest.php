<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cache\Tests;

use Joomla\Cache;
use Joomla\Registry\Registry;
use Joomla\Test\Helper;

/**
 * Tests for the Joomla\Cache\Cache class.
 *
 * @since  1.0
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    \Joomla\Cache\Cache
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Tests the Joomla\Cache\File::doDelete method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\File::doDelete
	 * @since   1.0
	 */
	public function testDoDelete()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Tests the Joomla\Cache\File::doGet method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testDoGet()
	{
		$this->assertNull($this->instance->get('foo'), 'Checks an unknown key.');
	}

	/**
	 * Tests the Joomla\Cache\File::doSet method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\File::doSet
	 * @covers  Joomla\Cache\File::doGet
	 * @covers  Joomla\Cache\File::doDelete
	 * @since   1.0
	 */
	public function testDoSet()
	{
		$fileName = Helper::invoke($this->instance, 'fetchStreamUri', 'foo');

		$this->assertFalse(file_exists($fileName));

		$this->instance->set('foo', 'bar');
		$this->assertTrue(file_exists($fileName), 'Checks the cache file was created.');

		$this->assertEquals('bar', $this->instance->get('foo'), 'Checks we got the cached value back.');

		$this->instance->delete('foo');
		$this->assertNull($this->instance->get('foo'), 'Checks for the delete.');
	}

	/**
	 * Tests the Joomla\Cache\File::fetchStreamUri method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\File::fetchStreamUri
	 * @since   1.0
	 */
	public function testFetchStreamUri()
	{
		$fileName = Helper::invoke($this->instance, 'fetchStreamUri', 'test');
	}

	/**
	 * Tests the Joomla\Cache\File::isExpired method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Cache\File::isExpired
	 * @since   1.0
	 */
	public function testIsExpired()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Setup the tests.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		// Clean up the test folder.
		$this->tearDown();

		$options = new Registry;
		$options->set('file.path', __DIR__ . '/Stubs');

		$this->instance = new Cache\File($options);
	}

	/**
	 * Teardown the test.
	 */
	protected function tearDown()
	{
		foreach (new \DirectoryIterator(__DIR__ . '/Stubs/') as $dir)
		{
			if ($dir->isDir() && strpos($dir->getFilename(), '~') === 0)
			{
				foreach (new \DirectoryIterator($dir->getRealPath()) as $file)
				{
					if ($file->isFile())
					{
						unlink($file->getRealPath());
					}
				}

				rmdir($dir->getRealPath());
			}
		}
	}
}
