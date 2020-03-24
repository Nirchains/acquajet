<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Encoder_Rfc2231Encoder implements Swift_Encoder
{
	private $_charStream;
	
	public function __construct(Swift_CharacterStream $charStream)
	{
		$this->_charStream = $charStream;
	}
	
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		$lines = array();
		$lineCount = 0;
		$lines[] = '';
		$currentLine =& $lines[$lineCount++];
		if (0 >= $maxLineLength)
		{
			$maxLineLength = 75;
		}
		
		$this->_charStream->flushContents();
		$this->_charStream->importString($string);
		$thisLineLength = $maxLineLength - $firstLineOffset;
		while (false !== ($char = $this->_charStream->read(4)))
		{
			$encodedChar = rawurlencode($char);
			if (0 != strlen($currentLine) && strlen($currentLine . $encodedChar) > $thisLineLength)
			{
				$lines[] = '';
				$currentLine =& $lines[$lineCount++];
				$thisLineLength = $maxLineLength;
			}
			
			$currentLine .= $encodedChar;
		}
		
		return implode("\r\n", $lines);
	}
	
	
	public function charsetChanged($charset)
	{
		$this->_charStream->setCharacterSet($charset);
	}
	
	
	public function __clone()
	{
		$this->_charStream = clone $this->_charStream;
	}

}