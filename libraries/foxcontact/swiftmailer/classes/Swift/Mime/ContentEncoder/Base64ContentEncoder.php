<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_ContentEncoder_Base64ContentEncoder extends Swift_Encoder_Base64Encoder implements Swift_Mime_ContentEncoder
{
	
	public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if (0 >= $maxLineLength || 76 < $maxLineLength)
		{
			$maxLineLength = 76;
		}
		
		$remainder = 0;
		$base64ReadBufferRemainderBytes = null;
		while (true)
		{
			$readBytes = $os->read(8192);
			$atEOF = $readBytes === false;
			if ($atEOF)
			{
				$streamTheseBytes = $base64ReadBufferRemainderBytes;
			}
			else
			{
				$streamTheseBytes = $base64ReadBufferRemainderBytes . $readBytes;
			}
			
			$base64ReadBufferRemainderBytes = null;
			$bytesLength = strlen($streamTheseBytes);
			if ($bytesLength === 0)
			{
				break;
			}
			
			if (!$atEOF)
			{
				$excessBytes = $bytesLength % 3;
				if ($excessBytes !== 0)
				{
					$base64ReadBufferRemainderBytes = substr($streamTheseBytes, -$excessBytes);
					$streamTheseBytes = substr($streamTheseBytes, 0, $bytesLength - $excessBytes);
				}
			
			}
			
			$encoded = base64_encode($streamTheseBytes);
			$encodedTransformed = '';
			$thisMaxLineLength = $maxLineLength - $remainder - $firstLineOffset;
			while ($thisMaxLineLength < strlen($encoded))
			{
				$encodedTransformed .= substr($encoded, 0, $thisMaxLineLength) . "\r\n";
				$firstLineOffset = 0;
				$encoded = substr($encoded, $thisMaxLineLength);
				$thisMaxLineLength = $maxLineLength;
				$remainder = 0;
			}
			
			if (0 < ($remainingLength = strlen($encoded)))
			{
				$remainder += $remainingLength;
				$encodedTransformed .= $encoded;
				$encoded = null;
			}
			
			$is->write($encodedTransformed);
			if ($atEOF)
			{
				break;
			}
		
		}
	
	}
	
	
	public function getName()
	{
		return 'base64';
	}

}