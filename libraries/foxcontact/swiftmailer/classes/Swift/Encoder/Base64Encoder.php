<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Encoder_Base64Encoder implements Swift_Encoder
{
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		if (0 >= $maxLineLength || 76 < $maxLineLength)
		{
			$maxLineLength = 76;
		}
		
		$encodedString = base64_encode($string);
		$firstLine = '';
		if (0 != $firstLineOffset)
		{
			$firstLine = substr($encodedString, 0, $maxLineLength - $firstLineOffset) . "\r\n";
			$encodedString = substr($encodedString, $maxLineLength - $firstLineOffset);
		}
		
		return $firstLine . trim(chunk_split($encodedString, $maxLineLength, "\r\n"));
	}
	
	
	public function charsetChanged($charset)
	{
	}

}