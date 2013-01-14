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

use Joomla\Factory;
use Joomla\Html\Html;
use Joomla\Log\Log;
use Joomla\Language\Text;

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.4
 */
class Field_Plugins extends Field_List
{
	/**
	 * The field type.
	 *
	 * @var    string
	 * @since  11.4
	 */
	protected $type = 'Plugins';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 *
	 * @since   11.4
	 */
	protected function getOptions()
	{
		$folder	= $this->element['folder'];

		if (!empty($folder))
		{
			// Get list of plugins
			$db     = Factory::getDbo();
			$query  = $db->getQuery(true);
			$query->select('element AS value, name AS text');
			$query->from('#__extensions');
			$query->where('folder = ' . $db->q($folder));
			$query->where('enabled = 1');
			$query->order('ordering, name');
			$db->setQuery($query);

			$options = $db->loadObjectList();

			$lang = Factory::getLanguage();

			foreach ($options as $i => $item)
			{
				$source = JPATH_PLUGINS . '/' . $folder . '/' . $item->value;
				$extension = 'plg_' . $folder . '_' . $item->value;
					$lang->load($extension . '.sys', JPATH_ADMINISTRATOR, null, false, false)
				||	$lang->load($extension . '.sys', $source, null, false, false)
				||	$lang->load($extension . '.sys', JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
				||	$lang->load($extension . '.sys', $source, $lang->getDefault(), false, false);
				$options[$i]->text = Text::_($item->text);
			}
		}
		else
		{
			Log::add(Text::_('JFRAMEWORK_FORM_FIELDS_PLUGINS_ERROR_FOLDER_EMPTY'), Log::WARNING, 'jerror');
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
