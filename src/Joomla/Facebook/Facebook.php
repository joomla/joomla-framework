<?php
/**
 * Part of the Joomla Framework Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook;

use Joomla\Registry\Registry;
use Joomla\Http\Http;

/**
 * Joomla Framework class for interacting with a Facebook API instance.
 *
 * @since  1.0
 */
class Facebook
{
	/**
	 * @var    Joomla\Registry\Registry  Options for the Facebook object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    Joomla\Http\Http  The HTTP client object to use in sending HTTP requests.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    Joomla\Facebook\OAuth  The OAuth client.
	 * @since  1.0
	 */
	protected $oauth;

	/**
	 * @var    Joomla\Facebook\User  Facebook API object for user.
	 * @since  1.0
	 */
	protected $user;

	/**
	* @var    Joomla\Facebook\Status  Facebook API object for status.
	* @since  1.0
	*/
	protected $status;

	/**
	* @var    Jooomla\Facebook\Checkin  Facebook API object for checkin.
	* @since  1.0
	*/
	protected $checkin;

	/**
	* @var    Joomla\Facebook\Event  Facebook API object for event.
	* @since  1.0
	*/
	protected $event;

	/**
	* @var    Joomla\Facebook\Group  Facebook API object for group.
	* @since  1.0
	*/
	protected $group;

	/**
	* @var    Joomla\Facebook\Link  Facebook API object for link.
	* @since  1.0
	*/
	protected $link;

	/**
	* @var    Joomla\Facebook\Note  Facebook API object for note.
	* @since  1.0
	*/
	protected $note;

	/**
	* @var    Joomla\Facebook\Post  Facebook API object for post.
	* @since  1.0
	*/
	protected $post;

	/**
	* @var    Joomla\Facebook\Comment  Facebook API object for comment.
	* @since  1.0
	*/
	protected $comment;

	/**
	* @var    Joomla\Facebook\Photo  Facebook API object for photo.
	* @since  1.0
	*/
	protected $photo;

	/**
	* @var    Joomla\Facebook\Video  Facebook API object for video.
	* @since  1.0
	*/
	protected $video;

	/**
	* @var    Joomla\Facebook\Album  Facebook API object for album.
	* @since  1.0
	*/
	protected $album;

	/**
	 * Constructor.
	 *
	 * @param   OAuth     $oauth    OAuth client.
	 * @param   Registry  $options  Facebook options object.
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
		$this->options->def('api.url', 'https://graph.facebook.com/');
	}

	/**
	 * Magic method to lazily create API objects
	 *
	 * @param   string  $name  Name of property to retrieve
	 *
	 * @return  FacebookObject  Facebook API object (status, user, friends etc).
	 *
	 * @since   1.0
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'user':
				if ($this->user == null)
				{
					$this->user = new User($this->options, $this->client, $this->oauth);
				}
				return $this->user;

			case 'status':
				if ($this->status == null)
				{
					$this->status = new Status($this->options, $this->client, $this->oauth);
				}
				return $this->status;

			case 'checkin':
				if ($this->checkin == null)
				{
					$this->checkin = new Checkin($this->options, $this->client, $this->oauth);
				}
				return $this->checkin;

			case 'event':
				if ($this->event == null)
				{
					$this->event = new Event($this->options, $this->client, $this->oauth);
				}
				return $this->event;

			case 'group':
				if ($this->group == null)
				{
					$this->group = new Group($this->options, $this->client, $this->oauth);
				}
				return $this->group;

			case 'link':
				if ($this->link == null)
				{
					$this->link = new Link($this->options, $this->client, $this->oauth);
				}
				return $this->link;

			case 'note':
				if ($this->note == null)
				{
					$this->note = new Note($this->options, $this->client, $this->oauth);
				}
				return $this->note;

			case 'post':
				if ($this->post == null)
				{
					$this->post = new Post($this->options, $this->client, $this->oauth);
				}
				return $this->post;

			case 'comment':
				if ($this->comment == null)
				{
					$this->comment = new Comment($this->options, $this->client, $this->oauth);
				}
				return $this->comment;

			case 'photo':
				if ($this->photo == null)
				{
					$this->photo = new Photo($this->options, $this->client, $this->oauth);
				}
				return $this->photo;

			case 'video':
				if ($this->video == null)
				{
					$this->video = new Video($this->options, $this->client, $this->oauth);
				}
				return $this->video;

			case 'album':
				if ($this->album == null)
				{
					$this->album = new Album($this->options, $this->client, $this->oauth);
				}
				return $this->album;
		}
	}

	/**
	 * Get an option from the Facebook instance.
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
	 * Set an option for the Facebook instance.
	 *
	* @param   string  $key    The name of the option to set.
	* @param   mixed   $value  The option value to set.
	*
	* @return  Facebook  This object for method chaining.
	*
	* @since   1.0
	*/
	public function setOption($key, $value)
	{
		$this->options->set($key, $value);

		return $this;
	}
}
