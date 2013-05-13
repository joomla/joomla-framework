<?php
/**
 * Part of the Joomla Framework Facebook Package
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Facebook;

/**
 * Facebook API Note class for the Joomla Framework.
 *
 * @see    http://developers.facebook.com/docs/reference/api/note/
 * @since  1.0
 */
class Note extends Object
{
	/**
	 * Method to get a note. Requires authentication and user_notes or friends_notes permission for non-public notes.
	 *
	 * @param   string  $note  The note id.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getNote($note)
	{
		return $this->get($note);
	}

	/**
	 * Method to get a note's comments. Requires authentication and user_notes or friends_notes permission for non-public notes.
	 *
	 * @param   string   $note    The note id.
	 * @param   integer  $limit   The number of objects per page.
	 * @param   integer  $offset  The object's number on the page.
	 * @param   string   $until   A unix timestamp or any date accepted by strtotime.
	 * @param   string   $since   A unix timestamp or any date accepted by strtotime.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getComments($note, $limit = 0, $offset = 0, $until = null, $since = null)
	{
		return $this->getConnection($note, 'comments', '', $limit, $offset, $until, $since);
	}

	/**
	 * Method to comment on a note. Requires authentication and publish_stream and user_notes or friends_notes permissions.
	 *
	 * @param   string  $note     The note id.
	 * @param   string  $message  The comment's text.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function createComment($note, $message)
	{
		// Set POST request parameters.
		$data = array();
		$data['message'] = $message;

		return $this->createConnection($note, 'comments', $data);
	}

	/**
	 * Method to delete a comment. Requires authentication and publish_stream and user_notes or friends_notes permissions.
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
	 * Method to get note's likes. Requires authentication and user_notes or friends_notes for non-public notes.
	 *
	 * @param   string   $note    The note id.
	 * @param   integer  $limit   The number of objects per page.
	 * @param   integer  $offset  The object's number on the page.
	 * @param   string   $until   A unix timestamp or any date accepted by strtotime.
	 * @param   string   $since   A unix timestamp or any date accepted by strtotime.
	 *
	 * @return  mixed   The decoded JSON response or false if the client is not authenticated.
	 *
	 * @since   1.0
	 */
	public function getLikes($note, $limit = 0, $offset = 0, $until = null, $since = null)
	{
		return $this->getConnection($note, 'likes', '', $limit, $offset, $until, $since);
	}

	/**
	 * Method to like a note. Requires authentication and publish_stream and user_notes or friends_notes permissions.
	 *
	 * @param   string  $note  The note id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function createLike($note)
	{
		return $this->createConnection($note, 'likes');
	}

	/**
	 * Method to unlike a note. Requires authentication and publish_stream and user_notes or friends_notes permissions.
	 *
	 * @param   string  $note  The note id.
	 *
	 * @return  boolean Returns true if successful, and false otherwise.
	 *
	 * @since   1.0
	 */
	public function deleteLike($note)
	{
		return $this->deleteConnection($note, 'likes');
	}
}
