<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\AbstractRegistryFormat;

/**
 * Test class for AbstractRegistryFormat.
 *
 * @since  1.0
 */
class AbstractRegistryFormatTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test the AbstractRegistryFormat::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		// Test INI format.
		$object = AbstractRegistryFormat::getInstance('INI');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Ini,
			$this->isTrue()
		);

		// Test JSON format.
		$object = AbstractRegistryFormat::getInstance('JSON');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Json,
			$this->isTrue()
		);

		// Test PHP format.
		$object = AbstractRegistryFormat::getInstance('PHP');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\PHP,
			$this->isTrue()
		);

		// Test XML format.
		$object = AbstractRegistryFormat::getInstance('XML');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Xml,
			$this->isTrue()
		);

		// Test non-existing format.
		try
		{
			$object = AbstractRegistryFormat::getInstance('SQL');
		}
		catch (Exception $e)
		{
			return;
		}
		$this->fail('AbstractRegistryFormat should throw an exception in case of non-existing formats');
	}
}
