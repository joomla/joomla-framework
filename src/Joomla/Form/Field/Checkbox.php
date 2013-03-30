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
 * Single check box field.
 * This is a boolean field with null for false and the specified option for true
 *
 * @link   http://www.w3.org/TR/html-markup/input.checkbox.html#input.checkbox
 * @see    JFormFieldCheckboxes
 * @since  1.0
 */
class Field_Checkbox extends Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Checkbox';

	/**
	 * Method to get the field input markup.
	 * The checked element sets the field to selected.
	 *
	 * @return  string   The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$value = $this->element['value'] ? (string) $this->element['value'] : '1';

		if (empty($this->value))
		{
			$checked = (isset($this->element['checked'] )) ? ' checked="checked"' : '';
		}
		else
		{
			$checked = ' checked="checked"';
		}

		// Initialize JavaScript field attributes.
		$onclick = $this->element['onclick'] ? ' onclick="' . (string) $this->element['onclick'] . '"' : '';

		return '<input type="checkbox" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '"' . $class . $checked . $disabled . $onclick . ' />';
	}
}
