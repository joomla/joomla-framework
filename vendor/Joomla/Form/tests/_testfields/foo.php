<?php
/**
 * @package     Joomla\Framework\Tests
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla\Framework
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldFoo extends JFormField
{
	/**
	 * Method to get the field input.
	 *
	 * @return  string        The field input.
	 */
	protected function getInput()
	{
		return 'Foo';
	}
}
