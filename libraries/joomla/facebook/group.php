<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Facebook
 * 
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */


defined('JPATH_PLATFORM') or die();


/**
 * Facebook API Group class for the Joomla Platform.
 *
 * @package     Joomla.Platform
 * @subpackage  Facebook
 * 
 * @since       12.1
 */
class JFacebookGroup extends JFacebookObject
{
	/**
	 * Method to read a group.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token for public groups, user_groups or friends_groups permission for non-public groups.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function getGroup($group, $access_token)
	{
		$token = '?access_token=' . $access_token;

		$path = $group . $token;

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the group's wall.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token for public groups, user_groups or friends_groups permission for non-public groups.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function getFeed($group, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/feed' . $token;

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the group's members.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token for public groups, user_groups or friends_groups permission for non-public groups.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function getMembers($group, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/members' . $token;

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the group's docs.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token for public groups, user_groups or friends_groups permission for non-public groups.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function getDocs($group, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/docs' . $token;

		// Send the request.
		return $this->sendRequest($path);
	}

	/**
	 * Method to get the groups's picture.
	 * 
	 * @param   string  $group         The group id.    
	 * @param   string  $access_token  The Facebook access token with user_groups or friends_groups permission.
	 * @param   string  $type          To request a different photo use square | small | normal | large.
	 * 
	 * @return  string   The URL to the group's picture.
	 * 
	 * @since   12.1
	 */
	public function getPicture($group, $access_token=null, $type=null)
	{
		$token = '?access_token=' . $access_token;

		if ($type != null)
		{
			$type = '&type=' . $type;
		}
		else
		{
			$type = '';
		}

		// Build the request path.
		$path = $group . '/picture' . $token . $type;

		// Send the request.
		$response = $this->client->get($this->fetchUrl($path));

		return $response->headers['Location'];
	}

	/**
	 * Method to post a link on group's wall.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token with publish_stream permission.
	 * @param   string  $link          Link URL.
	 * @param   strin   $message       Link message.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function createLink($group, $access_token, $link, $message=null)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/feed' . $token;

		// Set POST request parameters.
		$data = array();
		$data['link'] = $link;
		$data['message'] = $message;

		// Send the post request.
		return $this->sendRequest($path, 'post', $data);
	}

	/**
	 * Method to delete a link.
	 * 
	 * @param   mixed   $link          The Link ID.
	 * @param   string  $access_token  The Facebook access token. 
	 * 
	 * @return  boolean   Returns true if successful, and false otherwise.
	 * 
	 * @since   12.1
	 */
	public function deleteLink($link, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $link . $token;

		// Send the delete request.
		return $this->sendRequest($path, 'delete');
	}

	/**
	 * Method to post on group's wall. Message or link parameter is required.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token with the publish_stream permission.
	 * @param   string  $message       Post message.
	 * @param   string  $link          Post URL.
	 * @param   string  $picture       Post thumbnail image (can only be used if link is specified) 
	 * @param   string  $name          Post name (can only be used if link is specified).
	 * @param   string  $caption       Post caption (can only be used if link is specified).
	 * @param   string  $description   Post description (can only be used if link is specified).
	 * @param   array   $actions       Post actions array of objects containing name and link.
	 *
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function createPost($group, $access_token, $message=null, $link=null, $picture=null, $name=null, $caption=null,
		$description=null, $actions=null)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/feed' . $token;

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;
		$data['link'] = $link;
		$data['name'] = $name;
		$data['caption'] = $caption;
		$data['description'] = $description;
		$data['actions'] = $actions;
		$data['picture'] = $picture;

		// Send the post request.
		return $this->sendRequest($path, 'post', $data);
	}

	/**
	 * Method to delete a post. Note: you can only delete the post if it was created by the current user.
	 * 
	 * @param   string  $post          The Post ID.
	 * @param   string  $access_token  The Facebook access token. 
	 * 
	 * @return  boolean   Returns true if successful, and false otherwise.
	 * 
	 * @since   12.1
	 */
	public function deletePost($post, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $post . $token;

		// Send the delete request.
		return $this->sendRequest($path, 'delete');
	}

	/**
	 * Method to post a status message on behalf of the user on the group's wall.
	 * 
	 * @param   string  $group         The group id.
	 * @param   string  $access_token  The Facebook access token with publish_stream permission.
	 * @param   string  $message       Status message content.
	 * 
	 * @return  array   The decoded JSON response.
	 * 
	 * @since   12.1
	 */
	public function createStatus($group, $access_token, $message)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $group . '/feed' . $token;

		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		// Send the post request.
		return $this->sendRequest($path, 'post', $data);
	}

	/**
	 * Method to delete a status. Note: you can only delete the status if it was created by the current user.
	 * 
	 * @param   string  $status        The Status ID.
	 * @param   string  $access_token  The Facebook access token. 
	 * 
	 * @return  boolean Returns true if successful, and false otherwise.
	 * 
	 * @since   12.1
	 */
	public function deleteStatus($status, $access_token)
	{
		$token = '?access_token=' . $access_token;

		// Build the request path.
		$path = $status . $token;

		// Send the delete request.
		return $this->sendRequest($path, 'delete');
	}
}