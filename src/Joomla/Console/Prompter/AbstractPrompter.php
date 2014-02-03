<?php
/**
 * Part of jframework project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Console\Prompter;

use Joomla\Application\Cli\Output\Stdout;
use Joomla\Input;

/**
 * Class AbstractPrompter
 *
 * @since 1.0
 */
abstract class AbstractPrompter implements PrompterInterface
{
	/**
	 * Property input.
	 *
	 * @var  Input\Cli
	 */
	protected $input = null;

	/**
	 * Property output.
	 *
	 * @var  Stdout
	 */
	protected $output = null;

	/**
	 * Property inputStream.
	 *
	 * @var  resource
	 */
	protected $inputStream = STDIN;

	/**
	 * Constructor.
	 *
	 * @param Input\Cli $input
	 * @param Stdout    $output
	 */
	function __construct(Input\Cli $input = null, Stdout $output = null)
	{
		$this->input  = $input  ? : new Input\Cli;
		$this->output = $output ? : new Stdout;
	}

	/**
	 * ask
	 *
	 * @param string $msg
	 * @param string $default
	 *
	 * @return  mixed
	 */
	abstract public function ask($msg = '', $default = '');

	/**
	 * Get a value from standard input.
	 *
	 * @param   string  $question  The question you want to ask user.
	 *
	 * @return  string  The input string from standard input.
	 *
	 * @since   1.0
	 */
	public function in($question = '')
	{
		if ($question)
		{
			$this->output->out($question, false);
		}

		return rtrim(fread($this->inputStream, 8192), "\n");
	}

	/**
	 * getInput
	 *
	 * @return  Input\Cli
	 */
	public function getInput()
	{
		return $this->input;
	}

	/**
	 * setInput
	 *
	 * @param   Input\Cli  $input
	 *
	 * @return  AbstractPrompter  Return self to support chaining.
	 */
	public function setInput($input)
	{
		$this->input = $input;

		return $this;
	}

	/**
	 * getOutput
	 *
	 * @return  Stdout
	 */
	public function getOutput()
	{
		return $this->output;
	}

	/**
	 * setOutput
	 *
	 * @param   Stdout  $output
	 *
	 * @return  AbstractPrompter  Return self to support chaining.
	 */
	public function setOutput($output)
	{
		$this->output = $output;

		return $this;
	}

	/**
	 * getInputStream
	 *
	 * @return  resource
	 */
	public function getInputStream()
	{
		return $this->inputStream;
	}

	/**
	 * setInputStream
	 *
	 * @param   resource  $inputStream
	 *
	 * @return  AbstractPrompter  Return self to support chaining.
	 */
	public function setInputStream($inputStream)
	{
		$this->inputStream = $inputStream;

		return $this;
	}
}
 