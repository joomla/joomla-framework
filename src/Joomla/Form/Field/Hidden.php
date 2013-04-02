<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

/**
 * Form Field class for the Joomla Framework.
 * Provides a hidden field
 *
 * @link   http://www.w3.org/TR/html-markup/input.hidden.html#input.hidden
 * @since  1.0
 */
class Field_Hidden extends Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Hidden';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $disabled . $onchange . ' />';
	}
}
