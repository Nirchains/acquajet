<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_CharacterReader
{
	const MAP_TYPE_INVALID = 1;
	const MAP_TYPE_FIXED_LEN = 2;
	const MAP_TYPE_POSITIONS = 3;
	
	public function getCharPositions($string, $startOffset, &$currentMap, &$ignoredChars);
	
	public function getMapType();
	
	public function validateByteSequence($bytes, $size);
	
	public function getInitialByteSize();
}