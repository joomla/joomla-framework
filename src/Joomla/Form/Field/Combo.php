<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form;

use Joomla\Html\Html;
use Joomla\Language\Text;

/**
 * Form Field class for the Joomla Framework.
 * Implements a combo box field.
 *
 * @since  1.0
 */
class Field_Combo extends Field_List
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.0
	 */
	public $type = 'Combo';

	/**
	 * Method to get the field input markup for a combo box field.
	 *
	 * @return  string   The field input markup.
	 *
	 * @since   1.0
	 */
	protected function getInput()
	{
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="combobox ' . (string) $this->element['class'] . '"' : ' class="combobox"';
		$attr .= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// Get the field options.
		$options = $this->getOptions();

		// Load the combobox behavior.
		Html::_('behavior.combobox');

		// Build the input for the combo box.
		$html[] = '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $attr . '/>';

		// Build the list for the combo box.
		$html[] = '<ul id="combobox-' . $this->id . '" style="display:none;">';

		foreach ($options as $option)
		{
			$html[] = '<li>' . $option->text . '</li>';
		}

		$html[] = '</ul>';

		return implode($html);
	}
}
