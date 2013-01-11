<?php
/**
 * @package     Joomla.Platform
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View;

defined('JPATH_PLATFORM') or die;

use Joomla\Model\Model;

/**
 * Joomla Platform Base View Class
 *
 * @package     Joomla.Platform
 * @subpackage  View
 * @since       12.1
 */
abstract class Base implements View
{
	/**
	 * The model object.
	 *
	 * @var    Model
	 * @since  12.1
	 */
	protected $model;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   Model  $model  The model object.
	 *
	 * @since   12.1
	 */
	public function __construct(Model $model)
	{
		// Setup dependencies.
		$this->model = $model;
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string  $output  The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @see     View::escape()
	 * @since   12.1
	 */
	public function escape($output)
	{
		return $output;
	}
}
