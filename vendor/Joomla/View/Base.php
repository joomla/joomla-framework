<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View;

use Joomla\Model\Model;

/**
 * Joomla Platform Base View Class
 *
 * @package  Joomla\Framework
 * @since    1.0
 */
abstract class Base implements View
{
	/**
	 * The model object.
	 *
	 * @var    Model
	 * @since  1.0
	 */
	protected $model;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   Model  $model  The model object.
	 *
	 * @since   1.0
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
	 * @since   1.0
	 */
	public function escape($output)
	{
		return $output;
	}
}
