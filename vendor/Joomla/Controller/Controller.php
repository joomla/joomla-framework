<?php
/**
 * @package     Joomla\Framework
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller;


use Serializable;

/**
 * Joomla Platform Controller Interface
 *
 * @package     Joomla\Framework
 * @subpackage  Controller
 * @since       12.1
 */
interface Controller extends Serializable
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   12.1
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute();

	/**
	 * Get the application object.
	 *
	 * @return  JApplicationBase  The application object.
	 *
	 * @since   12.1
	 */
	public function getApplication();

	/**
	 * Get the input object.
	 *
	 * @return  JInput  The input object.
	 *
	 * @since   12.1
	 */
	public function getInput();
}
