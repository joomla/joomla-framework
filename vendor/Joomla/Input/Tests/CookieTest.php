<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Input\Tests;

use Joomla\Input\Cookie;

/**
 * Test class for JInputCookie.
 *
 * @since  1.0
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Cookie
	 * @since  1.0
	 */
	private $instance;

	/**
	 * Test the Joomla\Input\Cookie::set method.
	 *
	 * @return  void
	 *
	 * @covers  Joomla\Input\Cookie::set
	 * @since   1.0
	 */
	public function testSet()
	{
		$this->markTestIncomplete();
	}

	/**
	 * Sets up the fixture.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->instance = new Cookie;
	}
}
