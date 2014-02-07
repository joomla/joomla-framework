<?php
/**
 * Part of the Joomla Framework Console Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Option;

use Joomla\Input\Cli as Input;

/**
 * The cli option class.
 *
 * @since  1.0
 */
class Option
{
	const IS_GLOBAL = true;

	const IS_PRIVATE = false;

	/**
	 * Option name.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $name;

	/**
	 * Option alias.
	 *
	 * @var  array
	 *
	 * @since  1.0
	 */
	protected $alias = array();

	/**
	 * Option description.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $description;

	/**
	 * Global option or not.
	 *
	 * @var  boolean
	 *
	 * @since  1.0
	 */
	protected $global;

	/**
	 * The default when option not sent.
	 *
	 * @var  string
	 *
	 * @since  1.0
	 */
	protected $default;

	/**
	 * Cli Input object.
	 *
	 * @var Input
	 *
	 * @since  1.0
	 */
	protected $input;

	/**
	 * The option value cache.
	 *
	 * @var string
	 *
	 * @since  1.0
	 */
	protected $value;

	/**
	 * Class Constructor.
	 *
	 * @param   mixed    $alias        The option name. Can be a string, an array or an object.
	 *                                  If we use array, the first element will be option name, others will be alias.
	 * @param   mixed    $default      The default value when we get a non-exists option.
	 * @param   string   $description  The option description.
	 * @param   boolean  $global       True is a global option.
	 *
	 * @since   1.0
	 */
	public function __construct($alias, $default = null, $description = null, $global = false)
	{
		$alias = (array) $alias;
		$name  = array_shift($alias);

		$this->name        = $name;
		$this->default     = $default;
		$this->description = $description;
		$this->global      = $global;

		if (count($alias))
		{
			$this->setAlias($alias);
		}
	}

	/**
	 * Alias setter.
	 *
	 * @param   string  $alias  The option alias.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	/**
	 * Alias getter.
	 *
	 * @return array  The option alias.
	 *
	 * @since  1.0
	 */
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * Default value getter.
	 *
	 * @param   mixed  $default  The default value.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setDefault($default)
	{
		$this->default = $default;

		return $this;
	}

	/**
	 * Default value getter.
	 *
	 * @return string  The default value.
	 *
	 * @since  1.0
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * Description setter.
	 *
	 * @param   string  $description  The description.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Description getter.
	 *
	 * @return  string  The description.
	 *
	 * @since   1.0
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Name setter.
	 *
	 * @param   string  $name  Name of this option.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Name getter.
	 *
	 * @return  string  Name of this option.
	 *
	 * @since   1.0
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get Cli Input object.
	 *
	 * @return  Input  The Cli Input object.
	 *
	 * @since   1.0
	 */
	public function getInput()
	{
		if (!$this->input)
		{
			$this->input = new Input;
		}

		return $this->input;
	}

	/**
	 * Set Cli Input object.
	 *
	 * @param   Input  $input  The Cli Input object.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setInput(Input $input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * Get the value of this option which sent from command line.
	 *
	 * @return  mixed  The value of this option.
	 *
	 * @since   1.0
	 */
	public function getValue()
	{
		$input = $this->getInput();

		$name = $this->name;

		if ($input->getString($name))
		{
			return $input->getString($name);
		}

		foreach ($this->alias as $alias)
		{
			if ($input->getString($alias))
			{
				return $input->getString($alias);
			}
		}

		return $this->default;
	}

	/**
	 * Is this a global option?
	 *
	 * @return  bool  True is a global option.
	 *
	 * @since   1.0
	 */
	public function isGlobal()
	{
		return $this->global;
	}

	/**
	 * Set this option is global or not.
	 *
	 * @param   boolean  $global  True is a global option.
	 *
	 * @return  Option  Return this object to support chaining.
	 *
	 * @since   1.0
	 */
	public function setGlobal($global)
	{
		$this->global = $global;

		return $this;
	}
}
