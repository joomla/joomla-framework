<?php
/**
 * Part of the Joomla Framework Linkedin Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Linkedin;

use Joomla\Registry\Registry;
use Joomla\Http\Http;
use Joomla\Linkedin\OAuth;
use Joomla\Linkedin\People;
use Joomla\Linkedin\Groups;
use Joomla\Linkedin\Communications;
use Joomla\Linkedin\Companies;
use Joomla\Linkedin\Stream;
use Joomla\Linkedin\Jobs;

/**
 * Joomla Framework class for interacting with a Linkedin API instance.
 *
 * @since  1.0
 */
class Linkedin
{
	/**
	 * @var    JRegistry  Options for the Linkedin object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    OAuth The OAuth client.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    JLinkedinPeople  Linkedin API object for people.
	 * @since  1.0
	 */
	protected $people;

	/**
	 * @var    JLinkedinGroups  Linkedin API object for groups.
	 * @since  1.0
	 */
	protected $groups;

	/**
	 * @var    JLinkedinCompanies  Linkedin API object for companies.
	 * @since  1.0
	 */
	protected $companies;

	/**
	 * @var    JLinkedinJobs  Linkedin API object for jobs.
	 * @since  1.0
	 */
	protected $jobs;

	/**
	 * @var    JLinkedinStream  Linkedin API object for social stream.
	 * @since  1.0
	 */
	protected $stream;

	/**
	 * @var    JLinkedinCommunications  Linkedin API object for communications.
	 * @since  1.0
	 */
	protected $communications;

	/**
	 * Constructor.
	 *
	 * @param   OAuth     $oauth    The Linkedin OAuth client.
	 * @param   Registry  $options  Linkedin options object.
	 * @param   Http      $client   The HTTP client object.
	 *
	 * @since   1.0
	 */
	public function __construct(OAuth $oauth = null, Registry $options = null, Http $client = null)
	{
		$this->oauth = $oauth;
		$this->options = isset($options) ? $options : new Registry;
		$this->client  = isset($client) ? $client : new Http($this->options);

		// Setup the default API url if not already set.
		$this->options->def('api.url', 'https://api.linkedin.com');
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  Object  Linkedin API object (statuses, users, favorites, etc.).
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'people':
				if ($this->people == null)
				{
					$this->people = new People($this->options, $this->client, $this->oauth);
				}

				return $this->people;

			case 'groups':
				if ($this->groups == null)
				{
					$this->groups = new Groups($this->options, $this->client, $this->oauth);
				}

				return $this->groups;

			case 'companies':
				if ($this->companies == null)
				{
					$this->companies = new Companies($this->options, $this->client, $this->oauth);
				}

				return $this->companies;

			case 'jobs':
				if ($this->jobs == null)
				{
					$this->jobs = new Jobs($this->options, $this->client, $this->oauth);
				}

				return $this->jobs;

			case 'stream':
				if ($this->stream == null)
				{
					$this->stream = new Stream($this->options, $this->client, $this->oauth);
				}

				return $this->stream;

			case 'communications':
				if ($this->communications == null)
				{
					$this->communications = new Communications($this->options, $this->client, $this->oauth);
				}

				return $this->communications;
		}
	}

	/**
	 * Get an option from the Linkedin instance.
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
	 * Set an option for the Linkedin instance.
	 *
	 * @param   string  $key    The name of the option to set.
	 * @param   mixed   $value  The option value to set.
	 *
	 * @return  Linkedin  This object for method chaining.
	 *
	 * @since   1.0
	 */
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
