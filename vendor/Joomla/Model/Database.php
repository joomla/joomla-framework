<?php
/**
 * @package    Joomla\Framework
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Model;

use Joomla\Database\Driver;
use Joomla\Registry\Registry;
use Joomla\Factory;

/**
 * Joomla Platform Database Model Class
 *
 * @package  Joomla\Framework
 * @since    1.0
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
	 * @param   Registry  $state  The model state.
	 * @param   Driver    $db     The database adpater.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $state = null, Driver $db = null)
	{
		parent::__construct($state);

		// Setup the model.
		$this->db = isset($db) ? $db : $this->loadDb();
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

	/**
	 * Load the database driver.
	 *
	 * @return  Driver  The database driver.
	 *
	 * @since   1.0
	 */
	protected function loadDb()
	{
		return Factory::getDbo();
	}
}
