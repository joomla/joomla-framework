<?php
/**
 * @package    Joomla\Framework\Test
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */


/**
 * Some class.
 *
 * @package  SomePackage
 *
 * @since    0
 */
class BogusLoad
{
	public $someMethodCalled = false;

	/**
	 * Some method.
	 *
	 * @return void
	 */
	public function someMethod ()
	{
		$this->someMethodCalled = true;
	}
}
