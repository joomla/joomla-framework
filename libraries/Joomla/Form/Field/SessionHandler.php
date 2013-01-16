<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

defined('JPATH_PLATFORM') or die;

use Joomla\Html\Html;
use Joomla\Language\Text;
use Joomla\Session\Session;

/**
 * Form Field class for the Joomla Platform.
 * Provides a select list of session handler options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class Field_SessionHandler extends Field_List
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'SessionHandler';

	/**
	 * Method to get the session handler field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		// Get the options from JSession.
		foreach (Session::getStores() as $store)
		{
			$options[] = Html::_('select.option', $store, Text::_('JLIB_FORM_VALUE_SESSION_' . $store), 'value', 'text');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
