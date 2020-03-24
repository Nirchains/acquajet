<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_ByteStream_ArrayByteStream implements Swift_InputByteStream, Swift_OutputByteStream
{
	private $_array = array();
	private $_arraySize = 0;
	private $_offset = 0;
	private $_mirrors = array();
	
	public function __construct($stack = null)
	{
		if (is_array($stack))
		{
			$this->_array = $stack;
			$this->_arraySize = count($stack);
		}
		elseif (is_string($stack))
		{
			$this->write($stack);
		}
		else
		{
			$this->_array = array();
		}
	
	}
	
	
	public function read($length)
	{
		if ($this->_offset == $this->_arraySize)
		{
			return false;
		}
		
		$end = $length + $this->_offset;
		$end = $this->_arraySize < $end ? $this->_arraySize : $end;
		$ret = '';
		for (; $this->_offset < $end; ++$this->_offset)
		{
			$ret .= $this->_array[$this->_offset];
		}
		
		return $ret;
	}
	
	
	public function write($bytes)
	{
		$to_add = str_split($bytes);
		foreach ($to_add as $value)
		{
			$this->_array[] = $value;
		}
		
		$this->_arraySize = count($this->_array);
		foreach ($this->_mirrors as $stream)
		{
			$stream->write($bytes);
		}
	
	}
	
	
	public function commit()
	{
	}
	
	
	public function bind(Swift_InputByteStream $is)
	{
		$this->_mirrors[] = $is;
	}
	
	
	public function unbind(Swift_InputByteStream $is)
	{
		foreach ($this->_mirrors as $k => $stream)
		{
			if ($is === $stream)
			{
				unset($this->_mirrors[$k]);
			}
		
		}
	
	}
	
	
	public function setReadPointer($byteOffset)
	{
		if ($byteOffset > $this->_arraySize)
		{
			$byteOffset = $this->_arraySize;
		}
		elseif ($byteOffset < 0)
		{
			$byteOffset = 0;
		}
		
		$this->_offset = $byteOffset;
	}
	
	
	public function flushBuffers()
	{
		$this->_offset = 0;
		$this->_array = array();
		$this->_arraySize = 0;
		foreach ($this->_mirrors as $stream)
		{
			$stream->flushBuffers();
		}
	
	}

}