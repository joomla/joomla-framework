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
 * @property-read  Package\Activity       $activity       GitHub API object for the activity package.
 * @property-read  Package\Authorization  $authorization  GitHub API object for the authorizations package.
 * @property-read  Package\Data           $data           GitHub API object for the data package.
 * @property-read  Package\Gists          $gists          GitHub API object for the gists package.
 * @property-read  Package\Gitignore      $gitignore      GitHub API object for the gitignore package.
 * @property-read  Package\Issues         $issues         GitHub API object for the issues package.
 * @property-read  Package\Markdown       $markdown       GitHub API object for the the markdown package.
 * @property-read  Package\Meta           $meta           GitHub API object for the meta package.
 * @property-read  Package\Orgs           $orgs           GitHub API object for the the orgs package.
 * @property-read  Package\Pulls          $pulls          GitHub API object for the pulls package.
 * @property-read  Package\Repositories   $repositories   GitHub API object for the repositories package.
 * @property-read  Package\Search         $search         GitHub API object for the search package.
 * @property-read  Package\Users          $users          GitHub API object for the users package.
 * @property-read  Package\Zen            $zen            GitHub API object for the zen package.
 *
 * @since  1.0
 */
class Github
{
	/**
	 * @var    array  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

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
		if (!$this->getOption('api.url'))
		{
			$this->setOption('api.url', 'https://api.github.com');
		}
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
		$class = 'Joomla\\Github\\Package\\' . ucfirst($name);

		if (class_exists($class))
		{
			if (false == isset($this->$name))
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
		return isset($this->options[$key]) ? $this->options[$key] : null;
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
		$this->options[$key] = $value;

		return $this;
	}
}
