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
use Joomla\Language\Helper as LanguageHelper;

/**
 * Form Field class for the Joomla Platform.
 * Supports a list of installed application languages
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @see         JFormFieldContentLanguage for a select list of content languages.
 * @since       11.1
 */
class Field_Language extends Field_List
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Language';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize some field attributes.
		$client = (string) $this->element['client'];

		if ($client != 'site' && $client != 'administrator')
		{
			$client = 'site';
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(
			parent::getOptions(),
			LanguageHelper::createLanguageList($this->value, constant('JPATH_' . strtoupper($client)), true, true)
		);

		return $options;
	}
}
