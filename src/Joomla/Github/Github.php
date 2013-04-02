<?php
/**
 * Part of the Joomla Framework Github Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github;

use Joomla\Registry\Registry;

/**
 * Joomla Framework class for interacting with a GitHub server instance.
 *
 * @property-read  Gists       $gists       GitHub API object for gists.
 * @property-read  Issues      $issues      GitHub API object for issues.
 * @property-read  Pulls       $pulls       GitHub API object for pulls.
 * @property-read  Refs        $refs        GitHub API object for referencess.
 * @property-read  Forks       $forks       GitHub API object for forks.
 * @property-read  Commits     $commits     GitHub API object for commits.
 * @property-read  Milestones  $milestones  GitHub API object for commits.
 * @property-read  Statuses    $statuses    GitHub API object for commits.
 * @property-read  Account     $account     GitHub API object for account references.
 * @property-read  Hooks       $hooks       GitHub API object for hooks.
 * @property-read  Meta        $meta        GitHub API object for meta.
 *
 * @since  1.0
 */
class Github
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Gists  GitHub API object for gists.
	 * @since  1.0
	 */
	protected $gists;

	/**
	 * @var    Issues  GitHub API object for issues.
	 * @since  1.0
	 */
	protected $issues;

	/**
	 * @var    Pulls  GitHub API object for pulls.
	 * @since  1.0
	 */
	protected $pulls;

	/**
	 * @var    Refs  GitHub API object for referencess.
	 * @since  1.0
	 */
	protected $refs;

	/**
	 * @var    Forks  GitHub API object for forks.
	 * @since  1.0
	 */
	protected $forks;

	/**
	 * @var    Commits  GitHub API object for commits.
	 * @since  1.0
	 */
	protected $commits;

	/**
	 * @var    Milestones  GitHub API object for milestones.
	 * @since  1.0
	 */
	protected $milestones;

	/**
	 * @var    Statuses  GitHub API object for statuses.
	 * @since  1.0
	 */
	protected $statuses;

	/**
	 * @var    Account  GitHub API object for account references.
	 * @since  1.0
	 */
	protected $account;

	/**
	 * @var    Hooks  GitHub API object for hooks.
	 * @since  1.0
	 */
	protected $hooks;

	/**
	 * @var    Meta  GitHub API object for meta.
	 * @since  1.0
	 */
	protected $meta;

	/**
	 * Constructor.
	 *
	 * @param   Registry  $options  GitHub options object.
	 * @param   Http      $client   The HTTP client object.
	 *
	 * @since   1.0
	 */
	public function __construct(Registry $options = null, Http $client = null)
	{
		$this->options = isset($options) ? $options : new Registry;
		$this->client  = isset($client) ? $client : new Http($this->options);

		// Setup the default API url if not already set.
		$this->options->def('api.url', 'https://api.github.com');
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  Object  GitHub API object (gists, issues, pulls, etc).
	 *
	 * @since   1.0
	 * @throws  \InvalidArgumentException If $name is not a valid sub class.
	 */
	public function __get($name)
	{
		$class = '\\Joomla\\Github\\' . ucfirst($name);

		if (class_exists($class))
		{
			if ($this->$name == null)
			{
				$this->$name = new $class($this->options, $this->client);
			}

			return $this->$name;
		}

		throw new \InvalidArgumentException(sprintf('Argument %s produced an invalid class name: %s', $name, $class));
	}

	/**
	 * Get an option from the GitHub instance.
	 *
	 * @param   string  $key  The name of the option to get.
	 *
	 * @return  mixed  The option value.
	 *
	 * @since   1.0
	 */
	public function getOption($key)
	{
		return $this->options->get($key);
	}

	/**
	 * Set an option for the GitHub instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  GitHub  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
