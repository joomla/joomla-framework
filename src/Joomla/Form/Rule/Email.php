<?php
/**
 * Part of the Joomla Framework Form Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Rule;

use Joomla\Form\Rule;
use Joomla\Form\Form;
use Joomla\Registry\Registry;
use SimpleXMLElement;

/**
 * Form Rule class for the Joomla Framework.
 *
 * @since  1.0
 */
class Email extends Rule
{
	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var    string
	 * @since  1.0
	 * @see    http://www.w3.org/TR/html-markup/input.email.html
	 */
	protected $regex = '^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$';

	/**
	 * Method to test the email address and optionally check for uniqueness.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 * @param   Registry          $input    An optional Registry object with the entire data set to validate against the entire form.
	 * @param   Form              $form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   1.0
	 */
	public function test(SimpleXMLElement $element, $value, $group = null, Registry $input = null, Form $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');

		if (!$required && empty($value))
		{
			return true;
		}

		// If the tld attribute is present, change the regular expression to require at least 2 characters for it.
		$tld = ((string) $element['tld'] == 'tld' || (string) $element['tld'] == 'required');

		if ($tld)
		{
			$this->regex = '^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]{2,})$';
		}

		// Determine if the multiple attribute is present
		$multiple = ((string) $element['multiple'] == 'true' || (string) $element['multiple'] == 'multiple');

		if ($multiple)
		{
			$values = explode(',', $value);
		}

		if (!$multiple)
		{
			// Test the value against the regular expression.
			if (!parent::test($element, $value, $group, $input, $form))
			{
				return false;
			}
		}
		else
		{
			foreach ($values as $value)
			{
				// Test the value against the regular expression.
				if (!parent::test($element, $value, $group, $input, $form))
				{
					return false;
				}
			}
		}

		return true;
	}
}
