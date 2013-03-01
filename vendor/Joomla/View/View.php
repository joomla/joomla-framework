<?php
/**
 * @package     Joomla\Framework
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View;


/**
 * Joomla Platform View Interface
 *
 * @package     Joomla\Framework
 * @subpackage  View
 * @since       12.1
 */
interface View
{
	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @since   12.1
	 */
	public function escape($output);

	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   12.1
	 * @throws  \RuntimeException
	 */
	public function render();
}
