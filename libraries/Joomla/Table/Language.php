<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Table;


use Joomla\Database\Driver;
use Joomla\Language\Text;

/**
 * Languages table.
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class Language extends Table
{
	/**
	 * Constructor
	 *
	 * @param   Driver  $db  Database driver object.
	 *
	 * @since   11.1
	 */
	public function __construct(Driver $db)
	{
		parent::__construct('#__languages', 'lang_id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function check()
	{
		if (trim($this->title) == '')
		{
			$this->setError(Text::_('JLIB_DATABASE_ERROR_LANGUAGE_NO_TITLE'));

			return false;
		}

		return true;
	}

	/**
	 * Overrides Table::store to check unique fields.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   11.4
	 */
	public function store($updateNulls = false)
	{
		// Verify that the sef field is unique
		$table = Table::getInstance('Language', 'JTable');

		if ($table->load(array('sef' => $this->sef)) && ($table->lang_id != $this->lang_id || $this->lang_id == 0))
		{
			$this->setError(Text::_('JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_SEF'));

			return false;
		}

		// Verify that the image field is unique
		if ($table->load(array('image' => $this->image)) && ($table->lang_id != $this->lang_id || $this->lang_id == 0))
		{
			$this->setError(Text::_('JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_IMAGE'));

			return false;
		}

		// Verify that the language code is unique
		if ($table->load(array('lang_code' => $this->lang_code)) && ($table->lang_id != $this->lang_id || $this->lang_id == 0))
		{
			$this->setError(Text::_('JLIB_DATABASE_ERROR_LANGUAGE_UNIQUE_LANG_CODE'));

			return false;
		}
		return parent::store($updateNulls);
	}
}
