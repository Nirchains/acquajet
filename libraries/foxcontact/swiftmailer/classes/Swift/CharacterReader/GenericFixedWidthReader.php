<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_CharacterReader_GenericFixedWidthReader implements Swift_CharacterReader
{
	private $_width;
	
	public function __construct($width)
	{
		$this->_width = $width;
	}
	
	
	public function getCharPositions($string, $startOffset, &$currentMap, &$ignoredChars)
	{
		$strlen = strlen($string);
		$ignored = $strlen % $this->_width;
		$ignoredChars = $ignored ? substr($string, -$ignored) : '';
		$currentMap = $this->_width;
		return ($strlen - $ignored) / $this->_width;
	}
	
	
	public function getMapType()
	{
		return self::MAP_TYPE_FIXED_LEN;
	}
	
	
	public function validateByteSequence($bytes, $size)
	{
		$needed = $this->_width - $size;
		return $needed > -1 ? $needed : -1;
	}
	
	
	public function getInitialByteSize()
	{
		return $this->_width;
	}

}