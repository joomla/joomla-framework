<?php
/**
 * Part of the Joomla Framework Model Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Model;

use Joomla\Database\Driver;
use Joomla\Registry\Registry;

/**
 * Joomla Framework Database Model Class
 *
 * @since  1.0
 */
abstract class Database extends Base
{
	/**
	 * The database driver.
	 *
	 * @var    Driver
	 * @since  1.0
	 */
	protected $db;

	/**
	 * Instantiate the model.
	 *
	 * @param   Driver    $db     The database adpater.
	 * @param   Registry  $state  The model state.
	 *
	 * @since   1.0
	 */
	public function __construct(Driver $db, Registry $state = null)
	{
		$this->db = $db;

		parent::__construct($state);
	}

	/**
	 * Get the database driver.
	 *
	 * @return  Driver  The database driver.
	 *
	 * @since   1.0
	 */
	public function getDb()
	{
		return $this->db;
	}

	/**
	 * Set the database driver.
	 *
	 * @param   Driver  $db  The database driver.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function setDb(Driver $db)
	{
		$this->db = $db;
	}
}
