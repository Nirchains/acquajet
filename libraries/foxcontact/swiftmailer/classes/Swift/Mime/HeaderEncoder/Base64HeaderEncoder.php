<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_HeaderEncoder_Base64HeaderEncoder extends Swift_Encoder_Base64Encoder implements Swift_Mime_HeaderEncoder
{
	
	public function getName()
	{
		return 'B';
	}
	
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0, $charset = 'utf-8')
	{
		if (strtolower($charset) === 'iso-2022-jp')
		{
			$old = mb_internal_encoding();
			mb_internal_encoding('utf-8');
			$newstring = mb_encode_mimeheader($string, $charset, $this->getName(), "\r\n");
			mb_internal_encoding($old);
			return $newstring;
		}
		
		return parent::encodeString($string, $firstLineOffset, $maxLineLength);
	}

}