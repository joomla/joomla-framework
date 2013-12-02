<?php
/**
 * Part of the Joomla Framework Controller Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Controller;

/**
 * Joomla Framework Base Controller Class
 *
 * @since  1.0
 */
class ActionController extends AbstractController
{
	/**
	 * Action-to-action mapping array.
	 *
	 * @var    array
	 * @since  1.0
	 */
	private $maps;

	/**
	 * Adds an alias map to another controller action.
	 *
	 * @param   string  $alias   The alias for another action.
	 * @param   string  $action  The action that should be used if the alias is executed.
	 *
	 * @return  ActionController
	 *
	 * @since   1.0
	 */
	public function addMap($alias, $action)
	{
		$this->maps[$alias] = $action;

		return $this;
	}

	/**
	 * Execute an action.
	 *
	 * @param   string  $action  An optional action
	 *
	 * @return  mixed
	 *
	 * @since   1.0
	 */
	public function execute($action = null)
	{
		$method = $this->getActionMethod($action);

		return $this->$method();
	}

	/**
	 * Default execution method.
	 *
	 * The developer is expected to override this method to implement default action handling.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  \RuntimeException
	 */
	protected function doExecute()
	{
		throw new \RuntimeException(sprintf('Default controller executor not implemented in `%s`', get_class($this)));
	}

	/**
	 * Gets the method name that for an action.
	 *
	 * @param   string  $action  The name of the action.
	 *
	 * @return  string  The name or the method to execute, or `doExecute` if no mapping is found.
	 *
	 * @since   1.0
	 */
	protected function getActionMethod($action)
	{
		if (isset($this->maps[$action]))
		{
			$action = $this->maps[$action];
		}

		$method =sprintf('do%s', $action);

		if (method_exists($this, $method))
		{
			return $method;
		}
		else
		{
			return 'doExecute';
		}
	}
}
