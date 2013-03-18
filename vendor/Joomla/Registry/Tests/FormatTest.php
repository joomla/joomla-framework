<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Registry\Format;

/**
 * Test class for Format.
 *
 * @since  1.0
 */
class FormatTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test the Format::getInstance method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance()
	{
		// Test INI format.
		$object = Format::getInstance('INI');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Ini,
			$this->isTrue()
		);

		// Test JSON format.
		$object = Format::getInstance('JSON');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Json,
			$this->isTrue()
		);

		// Test PHP format.
		$object = Format::getInstance('PHP');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\PHP,
			$this->isTrue()
		);

		// Test XML format.
		$object = Format::getInstance('XML');
		$this->assertThat(
			$object instanceof Joomla\Registry\Format\Xml,
			$this->isTrue()
		);

		// Test non-existing format.
		try
		{
			$object = Format::getInstance('SQL');
		}
		catch (Exception $e)
		{
			return;
		}
		$this->fail('Format should throw an exception in case of non-existing formats');
	}
}
