<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Database\Tests;

/**
 * Test class for Joomla\Database\GCloudsqli\GCloudsqliDriver.
 * We can re-use all tests from MySQLi except for isSupported
 * which needs a class name change
 *
 * @since  1.0
 */
class DriverGcloudsqliTest extends DriverMysqliTest
{
	/**
	 * Test isSupported method.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function testIsSupported()
	{
		$this->assertThat(\Joomla\Database\Gcloudsqli\GcloudsqliDriver::isSupported(), $this->isTrue(), __LINE__);
	}
}
