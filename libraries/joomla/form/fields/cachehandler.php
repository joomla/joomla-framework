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
use Joomla\Cache\Cache;
use Joomla\Language\Text;

/**
 * Form Field class for the Joomla Platform.
 * Provides a list of available cache handlers
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @see         Cache
 * @since       11.1
 */
class Field_CacheHandler extends Field_List
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'CacheHandler';

	/**
	 * Method to get the field options.
	 *
	 * @return  array    The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		// Convert to name => name array.
		foreach (Cache::getStores() as $store)
		{
			$options[] = Html::_('select.option', $store, Text::_('JLIB_FORM_VALUE_CACHE_' . $store), 'value', 'text');
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
