<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_ContentEncoder_RawContentEncoder implements Swift_Mime_ContentEncoder
{
	
	public function encodeString($string, $firstLineOffset = 0, $maxLineLength = 0)
	{
		return $string;
	}
	
	
	public function encodeByteStream(Swift_OutputByteStream $os, Swift_InputByteStream $is, $firstLineOffset = 0, $maxLineLength = 0)
	{
		while (false !== ($bytes = $os->read(8192)))
		{
			$is->write($bytes);
		}
	
	}
	
	
	public function getName()
	{
		return 'raw';
	}
	
	
	public function charsetChanged($charset)
	{
	}

}