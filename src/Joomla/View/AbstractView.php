<?php
/**
 * Part of the Joomla Framework View Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\View;

use Joomla\Model\ModelInterface;

/**
 * Joomla Framework Abstract View Class
 *
 * @since  1.0
 */
abstract class AbstractView implements ViewInterface
{
	/**
	 * The model object.
	 *
	 * @var    ModelInterface
	 * @since  1.0
	 */
	protected $model;

	/**
	 * Method to instantiate the view.
	 *
	 * @param   ModelInterface  $model  The model object.
	 *
	 * @since   1.0
	 */
	public function __construct(ModelInterface $model)
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
	 * @see     ViewInterface::escape()
	 * @since   1.0
	 */
	public function escape($output)
	{
		return $output;
	}
}
