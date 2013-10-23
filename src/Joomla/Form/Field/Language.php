<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

use Joomla\Language\Language;

/**
 * Form Field class for the Joomla Framework.
 * Supports a list of installed application languages
 *
 * @see    JFormFieldContentLanguage for a select list of content languages.
 * @since  1.0
 */
class Field_Language extends Field_List
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Language';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.0
	 */
	protected function getOptions()
	{
		$basePath = $this->element['base_path'] ? (string) $this->element['base_path'] : JPATH_ROOT;

		// Merge any additional options in the XML definition.
		$options = array_merge(
			parent::getOptions(),
			$this->createLanguageList($this->value, $basePath)
		);

		return $options;
	}

	/**
	 * Builds a list of the system languages which can be used in a select option
	 *
	 * @param   string  $selected  Client key for the area
	 * @param   string  $basePath  Base path to use
	 *
	 * @return  array  List of system languages
	 *
	 * @since   1.0
	 */
	protected function createLanguageList($selected, $basePath = JPATH_ROOT)
	{
		$list = array();

		// Cache activation
		$langs = Language::getKnownLanguages($basePath);

		foreach ($langs as $lang => $metadata)
		{
			$option = array();

			$option['text'] = $metadata['name'];
			$option['value'] = $lang;

			if ($lang == $selected)
			{
				$option['selected'] = 'selected="selected"';
			}

			$list[] = $option;
		}

		return $list;
	}
}
