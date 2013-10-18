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
 * Text field for passwords
 *
 * @link   http://www.w3.org/TR/html-markup/input.password.html#input.password
 * @note   Two password fields may be validated as matching using JFormRuleEquals
 * @since  1.0
 */
class Field_Password extends Field
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	protected $type = 'Password';

	/**
	 * Method to get the field input markup for password.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class		= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$auto		= ((string) $this->element['autocomplete'] == 'off') ? ' autocomplete="off"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		$script = '';

		return '<input type="password" name="' . $this->name . '" id="' . $this->id . '"' .
			' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' .
			$auto . $class . $readonly . $disabled . $size . $maxLength . '/>' . $script;
	}
}
