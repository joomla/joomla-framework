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
	 * Data provider for testGetInstance
	 *
	 * @return  void
	 */
	public function seedTestGetInstance()
	{
		return array(
			array('Xml'),
			array('Ini'),
			array('Json'),
			array('Php'),
			array('Yaml')
		);
	}

	/**
	 * Test the AbstractRegistryFormat::getInstance method.
	 *
	 * @dataProvider  seedTestGetInstance
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetInstance($format)
	{
		$class = '\\Joomla\\Registry\\Format\\' . $format;

		$object = AbstractRegistryFormat::getInstance($format);
		$this->assertThat(
			$object instanceof $class,
			$this->isTrue()
		);
	}

	/**
	 * Test getInstance with a non-existent format.
	 *
	 * @expectedException  \InvalidArgumentException
	 */
	public function testGetInstanceNonExistent()
	{
		AbstractRegistryFormat::getInstance('SQL');
	}
}
