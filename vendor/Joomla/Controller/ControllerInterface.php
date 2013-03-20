<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller;

/**
 * Joomla Platform Controller Interface
 *
 * @since  1.0
 */
interface ControllerInterface extends \Serializable
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   1.0
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute();

	/**
	 * Get the application object.
	 *
	 * @return  \Joomla\Application\Base  The application object.
	 *
	 * @since   1.0
	 */
	public function getApplication();

	/**
	 * Get the input object.
	 *
	 * @return  \Joomla\Input\Input  The input object.
	 *
	 * @since   1.0
	 */
	public function getInput();
}
