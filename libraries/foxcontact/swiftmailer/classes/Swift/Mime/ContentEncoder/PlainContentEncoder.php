<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_ContentEncoder_PlainContentEncoder implements Swift_Mime_ContentEncoder
{
	private $_name;
	private $_canonical;
	
	public function __construct($name, $canonical = false)
	{
		$this->_name = $name;
		$this->_canonical = $canonical;
	}
	
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if ($this->_canonical)
		{
			$string = $this->_canonicalize($string);
		}
		
		return $this->_safeWordWrap($string, $maxLineLength, "\r\n");
	}
	
	
	public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
	{
		$leftOver = '';
		while (false !== ($bytes = $os->read(8192)))
		{
			$toencode = $leftOver . $bytes;
			if ($this->_canonical)
			{
				$toencode = $this->_canonicalize($toencode);
			}
			
			$wrapped = $this->_safeWordWrap($toencode, $maxLineLength, "\r\n");
			$lastLinePos = strrpos($wrapped, "\r\n");
			$leftOver = substr($wrapped, $lastLinePos);
			$wrapped = substr($wrapped, 0, $lastLinePos);
			$is->write($wrapped);
		}
		
		if (strlen($leftOver))
		{
			$is->write($leftOver);
		}
	
	}
	
	
	public function getName()
	{
		return $this->_name;
	}
	
	
	public function charsetChanged($charset)
	{
	}
	
	
	private function _safeWordwrap($string, $length = 75, $le = "\r\n")
	{
		if (0 >= $length)
		{
			return $string;
		}
		
		$originalLines = explode($le, $string);
		$lines = array();
		$lineCount = 0;
		foreach ($originalLines as $originalLine)
		{
			$lines[] = '';
			$currentLine =& $lines[$lineCount++];
			$chunks = preg_split('/(?<=\\s)/', $originalLine);
			foreach ($chunks as $chunk)
			{
				if (0 != strlen($currentLine) && strlen($currentLine . $chunk) > $length)
				{
					$lines[] = '';
					$currentLine =& $lines[$lineCount++];
				}
				
				$currentLine .= $chunk;
			}
		
		}
		
		return implode("\r\n", $lines);
	}
	
	
	private function _canonicalize($string)
	{
		return str_replace(array("\r\n", "\r", "\n"), array("\n", "\n", "\r\n"), $string);
	}

}