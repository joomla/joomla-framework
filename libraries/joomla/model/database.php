<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Model;

defined('JPATH_PLATFORM') or die;

use Joomla\Database\Driver;
use Joomla\Registry\Registry;
use Joomla\Factory;

/**
 * Joomla Platform Database Model Class
 *
 * @package     Joomla.Platform
 * @subpackage  Model
 * @since       12.1
 */
abstract class Database extends Base
{
	/**
	 * The database driver.
	 *
	 * @var    Driver
	 * @since  12.1
	 */
	protected $db;

	/**
	 * Instantiate the model.
	 *
	 * @param   Registry  $state  The model state.
	 * @param   Driver    $db     The database adpater.
	 *
	 * @since   12.1
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
	 * @since   12.1
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
	 * @since   12.1
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
	 * @since   12.1
	 */
	protected function loadDb()
	{
		return Factory::getDbo();
	}
}
