<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_KeyCache_SimpleKeyCacheInputStream implements Swift_KeyCache_KeyCacheInputStream
{
	private $_keyCache;
	private $_nsKey;
	private $_itemKey;
	private $_writeThrough = null;
	
	public function setKeyCache(Swift_KeyCache $keyCache)
	{
		$this->_keyCache = $keyCache;
	}
	
	
	public function setWriteThroughStream(Swift_InputByteStream $is)
	{
		$this->_writeThrough = $is;
	}
	
	
	public function write($bytes, Swift_InputByteStream $is = null)
	{
		$this->_keyCache->setString($this->_nsKey, $this->_itemKey, $bytes, Swift_KeyCache::MODE_APPEND);
		if (isset($is))
		{
			$is->write($bytes);
		}
		
		if (isset($this->_writeThrough))
		{
			$this->_writeThrough->write($bytes);
		}
	
	}
	
	
	public function commit()
	{
	}
	
	
	public function bind(Swift_InputByteStream $is)
	{
	}
	
	
	public function unbind(Swift_InputByteStream $is)
	{
	}
	
	
	public function flushBuffers()
	{
		$this->_keyCache->clearKey($this->_nsKey, $this->_itemKey);
	}
	
	
	public function setNsKey($nsKey)
	{
		$this->_nsKey = $nsKey;
	}
	
	
	public function setItemKey($itemKey)
	{
		$this->_itemKey = $itemKey;
	}
	
	
	public function __clone()
	{
		$this->_writeThrough = null;
	}

}