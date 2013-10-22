<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Form\Tests;

/**
 * JFormInspector class.
 *
 * @since  1.0
 */
class JFormInspector extends \Joomla\Form\Form
{
	/**
	 * Test...
	 *
	 * @param   \SimpleXMLElement  $source  The source element on which to append.
	 * @param   \SimpleXMLElement  $new     The new element to append.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function addNode(\SimpleXMLElement $source, \SimpleXMLElement $new)
	{
		parent::addNode($source, $new);
	}

	/**
	 * Test...
	 *
	 * @param   \SimpleXMLElement  $source  The source element on which to append the attributes
	 * @param   \SimpleXMLElement  $new     The new element to append
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function mergeNode(\SimpleXMLElement $source, \SimpleXMLElement $new)
	{
		parent::mergeNode($source, $new);
	}

	/**
	 * Test...
	 *
	 * @param   \SimpleXMLElement  $source  The source element.
	 * @param   \SimpleXMLElement  $new     The new element to merge.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public static function mergeNodes(\SimpleXMLElement $source, \SimpleXMLElement $new)
	{
		parent::mergeNodes($source, $new);
	}

	/**
	 * Test...
	 *
	 * @param   string  $element  The XML element object representation of the form field.
	 * @param   mixed   $value    The value to filter for the field.
	 *
	 * @return  mixed   The filtered value.
	 *
	 * @since   1.0
	 */
	public function filterField($element, $value)
	{
		return parent::filterField($element, $value);
	}

	/**
	 * Test...
	 *
	 * @param   string  $name   The name of the form field.
	 * @param   string  $group  The optional dot-separated form group path on which to find the field.
	 *
	 * @return  mixed  The XML element object for the field or boolean false on error.
	 *
	 * @since   1.0
	 */
	public function findField($name, $group = null)
	{
		return parent::findField($name, $group);
	}

	/**
	 * Test...
	 *
	 * @param   string  $group  The dot-separated form group path on which to find the group.
	 *
	 * @return  mixed  An array of XML element objects for the group or boolean false on error.
	 *
	 * @since   1.0
	 */
	public function findGroup($group)
	{
		return parent::findGroup($group);
	}

	/**
	 * Test...
	 *
	 * @param   mixed    $group   The optional dot-separated form group path on which to find the fields.
	 *                            Null will return all fields. False will return fields not in a group.
	 * @param   boolean  $nested  True to also include fields in nested groups that are inside of the
	 *                            group for which to find fields.
	 *
	 * @return  mixed  Boolean false on error or array of SimpleXMLElement objects.
	 *
	 * @since   1.0
	 */
	public function findFieldsByGroup($group = null, $nested = false)
	{
		return parent::findFieldsByGroup($group, $nested);
	}

	/**
	 * Test...
	 *
	 * @param   string  $name  The name of the fieldset.
	 *
	 * @return  mixed  Boolean false on error or array of SimpleXMLElement objects.
	 *
	 * @since   1.0
	 */
	public function findFieldsByFieldset($name)
	{
		return parent::findFieldsByFieldset($name);
	}

	/**
	 * Test...
	 *
	 * @return  object
	 *
	 * @since   1.0
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Test...
	 *
	 * @return  array  Return the protected options array.
	 *
	 * @since   1.0
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Test...
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getXML()
	{
		return $this->xml;
	}

	/**
	 * Test...
	 *
	 * @param   string  $element  The XML element object representation of the form field.
	 * @param   string  $group    The optional dot-separated form group path on which to find the field.
	 * @param   mixed   $value    The optional value to use as the default for the field.
	 *
	 * @return  \Joomla\Form\Field
	 *
	 * @since   1.0
	 */
	public function loadField($element, $group = null, $value = null)
	{
		return parent::loadField($element, $group, $value);
	}

	/**
	 * Test...
	 *
	 * @param   \SimpleXMLElement          $element  The XML element object representation of the form field.
	 * @param   string                     $group    The optional dot-separated form group path on which to find the field.
	 * @param   mixed                      $value    The optional value to use as the default for the field.
	 * @param   \Joomla\Registry\Registry  $input    An optional Registry object with the entire data set to validate
	 *                                               against the entire form.
	 *
	 * @return  boolean
	 *
	 * @since   1.0
	 */
	public function validateField($element, $group = null, $value = null, $input = null)
	{
		return parent::validateField($element, $group, $value, $input);
	}
}

/**
 * JFormFieldInspector class.
 *
 * @since  1.0
 */
class JFormFieldInspector extends \Joomla\Form\Field
{
	/**
	 * Test...
	 *
	 * @param   string  $name  Element name
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		if ($name == 'element')
		{
			return $this->element;
		}
		else
		{
			return parent::__get($name);
		}
	}

	/**
	 * Test...
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function getInput()
	{
		return null;
	}

	/**
	 * Test...
	 *
	 * @return  \Joomla\Form\Form
	 *
	 * @since   1.0
	 */
	public function getForm()
	{
		return $this->form;
	}

	/**
	 * Test...
	 *
	 * @param   string  $fieldId    The field element id.
	 * @param   string  $fieldName  The field element name.
	 *
	 * @return  string  The id to be used for the field input tag.
	 *
	 * @since   1.0
	 */
	public function getId($fieldId, $fieldName)
	{
		return parent::getId($fieldId, $fieldName);
	}

	/**
	 * Test...
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getLabel()
	{
		return parent::getLabel();
	}

	/**
	 * Test...
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function getTitle()
	{
		return parent::getTitle();
	}
}
