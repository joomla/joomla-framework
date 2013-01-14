<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Google
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Google\Data;

defined('JPATH_PLATFORM') or die;

use Joomla\Google\Auth;
use Joomla\Google\Data;
use Joomla\Registry\Registry;

/**
 * Google+ data class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Google
 * @since       1234
 */
class Plus extends Data
{
	/**
	* @var    JGoogleDataPlusPeople  Google+ API object for people.
	* @since  12.3
	*/
	protected $people;

	/**
	* @var    JGoogleDataPlusActivities  Google+ API object for people.
	* @since  12.3
	*/
	protected $activities;

	/**
	* @var    JGoogleDataPlusComments  Google+ API object for people.
	* @since  12.3
	*/
	protected $comments;

	/**
	 * Constructor.
	 *
	 * @param   JRegistry    $options  Google options object
	 * @param   JGoogleAuth  $auth     Google data http client object
	 *
	 * @since   1234
	 */
	public function __construct(Registry $options = null, Auth $auth = null)
	{
		// Setup the default API url if not already set.
		$options->def('api.url', 'https://www.googleapis.com/plus/v1/');

		parent::__construct($options, $auth);

		if (isset($this->auth) && !$this->auth->getOption('scope'))
		{
			$this->auth->setOption('scope', 'https://www.googleapis.com/auth/plus.me');
		}
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  JGoogleDataPlus  Google+ API object (people, activities, comments).
	 *
	 * @since   12.3
	 */
	public function __get($name)
	{
		switch (strtolower($name))
		{
			case 'people':
				if ($this->people == null)
				{
					$this->people = new Plus\People($this->options, $this->auth);
				}
				return $this->people;

			case 'activities':
				if ($this->activities == null)
				{
					$this->activities = new Plus\Activities($this->options, $this->auth);
				}
				return $this->activities;

			case 'comments':
				if ($this->comments == null)
				{
					$this->comments = new Plus\Comments($this->options, $this->auth);
				}
				return $this->comments;
		}
	}
}
