<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Http\Tests;

use Joomla\Http\HttpFactory;

/**
 * Test class for Joomla\Http\HttpFactory.
 *
 * @since  1.0
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests the getHttp method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetHttp()
	{
		$this->assertThat(
			HttpFactory::getHttp(),
			$this->isInstanceOf('Joomla\\Http\\Http')
		);
	}

	/**
	 * Tests the getAvailableDriver method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testGetAvailableDriver()
	{
		$this->assertThat(
			HttpFactory::getAvailableDriver(array(), array()),
			$this->isFalse(),
			'Passing an empty array should return false due to there being no adapters to test'
		);

		$this->assertThat(
			HttpFactory::getAvailableDriver(array(), array('fopen')),
			$this->isFalse(),
			'A false should be returned if a class is not present or supported'
		);
	}
}
