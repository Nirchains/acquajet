<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_CharacterStream
{
	
	public function setCharacterSet($charset);
	
	public function setCharacterReaderFactory(Swift_CharacterReaderFactory $factory);
	
	public function importByteStream(Swift_OutputByteStream $os);
	
	public function importString($string);
	
	public function read($length);
	
	public function readBytes($length);
	
	public function write($chars);
	
	public function setPointer($charOffset);
	
	public function flushContents();
}