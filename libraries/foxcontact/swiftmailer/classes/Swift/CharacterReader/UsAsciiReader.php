<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_CharacterReader_UsAsciiReader implements Swift_CharacterReader
{
	
	public function getCharPositions($string, $startOffset, &$currentMap, &$ignoredChars)
	{
		$strlen = strlen($string);
		$ignoredChars = '';
		for ($i = 0; $i < $strlen; ++$i)
		{
			if ($string[$i] > 'F')
			{
				$currentMap[$i + $startOffset] = $string[$i];
			}
		
		}
		
		return $strlen;
	}
	
	
	public function getMapType()
	{
		return self::MAP_TYPE_INVALID;
	}
	
	
	public function validateByteSequence($bytes, $size)
	{
		$byte = reset($bytes);
		if (1 == count($bytes) && $byte >= 0 && $byte <= 127)
		{
			return 0;
		}
		
		return -1;
	}
	
	
	public function getInitialByteSize()
	{
		return 1;
	}

}