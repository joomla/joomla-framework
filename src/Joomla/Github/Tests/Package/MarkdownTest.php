<?php
/**
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Github\Tests;

use Joomla\Github\Package\Markdown;
use Joomla\Registry\Registry;

/**
 * Test class for Markdown.
 *
 * @since  1.0
 */
class MarkdownTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var    Registry  Options for the GitHub object.
	 * @since  1.0
	 */
	protected $options;

	/**
	 * @var    \PHPUnit_Framework_MockObject_MockObject  Mock client object.
	 * @since  1.0
	 */
	protected $client;

	/**
	 * @var    \Joomla\Http\Response  Mock response object.
	 * @since  1.0
	 */
	protected $response;

	/**
	 * @var Markdown
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 *
	 * @return void
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->options  = new Registry;
		$this->client = $this->getMock('\\Joomla\\Github\\Http', array('get', 'post', 'delete', 'patch', 'put'));
		$this->response = $this->getMock('\\Joomla\\Http\\Response');

		$this->object = new Markdown($this->options, $this->client);
	}

	/**
	 * Tests the render method
	 *
	 * @return  void
	 */
	public function testRender()
	{
		$this->response->code = 200;
		$this->response->body = '<p>Hello world <a href="http://github.com/github/linguist/issues/1" '
			. 'class="issue-link" title="This is a simple issue">github/linguist#1</a> <strong>cool</strong>, '
			. 'and <a href="http://github.com/github/gollum/issues/1" class="issue-link" '
			. 'title="This is another issue">#1</a>!</p>';

		$text    = 'Hello world github/linguist#1 **cool**, and #1!';
		$mode    = 'gfm';
		$context = 'github/gollum';

		$data = str_replace('\\/', '/', json_encode(
				array(
					'text'    => $text,
					'mode'    => $mode,
					'context' => $context
				)
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/markdown', $data, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->render($text, $mode, $context),
			$this->equalTo($this->response->body)
		);
	}

	/**
	 * Tests the renderInvalidMode method
	 *
	 * @return  void
	 *
	 * @expectedException  InvalidArgumentException
	 */
	public function testRenderInvalidMode()
	{
		$this->assertThat(
			$this->object->render('', 'xxx', 'github/gollum'),
			$this->equalTo('')
		);
	}

	/**
	 * Tests the renderFailure method
	 *
	 * @return  void
	 *
	 * @expectedException  \DomainException
	 */
	public function testRenderFailure()
	{
		$this->response->code = 404;
		$this->response->body = '';

		$text    = 'Hello world github/linguist#1 **cool**, and #1!';
		$mode    = 'gfm';
		$context = 'github/gollum';

		$data = str_replace('\\/', '/', json_encode(
				array(
					'text'    => $text,
					'mode'    => $mode,
					'context' => $context
				)
			)
		);

		$this->client->expects($this->once())
			->method('post')
			->with('/markdown', $data, 0, 0)
			->will($this->returnValue($this->response));

		$this->assertThat(
			$this->object->render($text, $mode, $context),
			$this->equalTo('')
		);
	}
}
