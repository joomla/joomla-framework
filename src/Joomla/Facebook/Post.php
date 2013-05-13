<?php
/**
 * Part of the Joomla Framework Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook;

/**
 * Facebook API Post class for the Joomla Framework.
 *
 * @see    http://developers.facebook.com/docs/reference/api/post/
 * @since  1.0
 */
class Post extends Object
{
	/**
	 * Method to get a post. Requires authentication and read_stream permission for all data.
	 *
	 * @param   string  $post  The post id.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getPost($post)
	{
		return $this->get($post);
	}

	/**
	 * Method to delete a post if it was created by this application. Requires authentication and publish_stream permission
	 *
	 * @param   string  $post  The post id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function deletePost($post)
	{
		return $this->deleteConnection($post);
	}

	/**
	 * Method to get a post's comments. Requires authentication and read_stream permission.
	 *
	 * @param   string   $post    The post id.
	 * @param   integer  $limit   The number of objects per page.
	 * @param   integer  $offset  The object's number on the page.
	 * @param   string   $until   A unix timestamp or any date accepted by strtotime.
	 * @param   string   $since   A unix timestamp or any date accepted by strtotime.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getComments($post, $limit = 0, $offset = 0, $until = null, $since = null)
	{
		return $this->getConnection($post, 'comments', '', $limit, $offset, $until, $since);
	}

	/**
	 * Method to comment on a post. Requires authentication and publish_stream permission
	 *
	 * @param   string  $post     The post id.
	 * @param   string  $message  The comment's text.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function createComment($post, $message)
	{
		// Set POST request parameters.
		$data['message'] = $message;

		return $this->createConnection($post, 'comments', $data);
	}

	/**
	 * Method to delete a comment. Requires authentication and publish_stream permission
	 *
	 * @param   string  $comment  The comment's id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function deleteComment($comment)
	{
		return $this->deleteConnection($comment);
	}

	/**
	 * Method to get post's likes. Requires authentication and read_stream permission.
	 *
	 * @param   string   $post    The post id.
	 * @param   integer  $limit   The number of objects per page.
	 * @param   integer  $offset  The object's number on the page.
	 * @param   string   $until   A unix timestamp or any date accepted by strtotime.
	 * @param   string   $since   A unix timestamp or any date accepted by strtotime.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getLikes($post, $limit = 0, $offset = 0, $until = null, $since = null)
	{
		return $this->getConnection($post, 'likes', '', $limit, $offset, $until, $since);
	}

	/**
	 * Method to like a post. Requires authentication and publish_stream permission
	 *
	 * @param   string  $post  The post id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function createLike($post)
	{
		return $this->createConnection($post, 'likes');
	}

	/**
	 * Method to unlike a post. Requires authentication and publish_stream permission
	 *
	 * @param   string  $post  The post id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function deleteLike($post)
	{
		return $this->deleteConnection($post, 'likes');
	}
}
