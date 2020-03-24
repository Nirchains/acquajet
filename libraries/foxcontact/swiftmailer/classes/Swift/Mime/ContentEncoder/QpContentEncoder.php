<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_ContentEncoder_QpContentEncoder extends Swift_Encoder_QpEncoder implements Swift_Mime_ContentEncoder
{
	protected $_dotEscape;
	
	public function __construct(Swift_CharacterStream $charStream, Swift_StreamFilter $filter = null, $dotEscape = false)
	{
		$this->_dotEscape = $dotEscape;
		parent::__construct($charStream, $filter);
	}
	
	
	public function __sleep()
	{
		return array('_charStream', '_filter', '_dotEscape');
	}
	
	
	protected function getSafeMapShareId()
	{
		return get_class($this) . ($this->_dotEscape ? '.dotEscape' : '');
	}
	
	
	protected function initSafeMap()
	{
		parent::initSafeMap();
		if ($this->_dotEscape)
		{
			unset($this->_safeMap[46]);
		}
	
	}
	
	
	public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if ($maxLineLength > 76 || $maxLineLength <= 0)
		{
			$maxLineLength = 76;
		}
		
		$thisLineLength = $maxLineLength - $firstLineOffset;
		$this->_charStream->flushContents();
		$this->_charStream->importByteStream($os);
		$currentLine = '';
		$prepend = '';
		$size = $lineLen = 0;
		while (false !== ($bytes = $this->_nextSequence()))
		{
			if (isset($this->_filter))
			{
				while ($this->_filter->shouldBuffer($bytes))
				{
					if (false === ($moreBytes = $this->_nextSequence(1)))
					{
						break;
					}
					
					foreach ($moreBytes as $b)
					{
						$bytes[] = $b;
					}
				
				}
				
				$bytes = $this->_filter->filter($bytes);
			}
			
			$enc = $this->_encodeByteSequence($bytes, $size);
			$i = strpos($enc, '=0D=0A');
			$newLineLength = $lineLen + ($i === false ? $size : $i);
			if ($currentLine && $newLineLength >= $thisLineLength)
			{
				$is->write($prepend . $this->_standardize($currentLine));
				$currentLine = '';
				$prepend = "=\r\n";
				$thisLineLength = $maxLineLength;
				$lineLen = 0;
			}
			
			$currentLine .= $enc;
			if ($i === false)
			{
				$lineLen += $size;
			}
			else
			{
				$lineLen = $size - strrpos($enc, '=0D=0A') - 6;
			}
		
		}
		
		if (strlen($currentLine))
		{
			$is->write($prepend . $this->_standardize($currentLine));
		}
	
	}
	
	
	public function getName()
	{
		return 'quoted-printable';
	}

}