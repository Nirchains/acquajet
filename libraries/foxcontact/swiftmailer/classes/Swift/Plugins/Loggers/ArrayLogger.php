<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_Loggers_ArrayLogger implements Swift_Plugins_Logger
{
	private $_log = array();
	private $_size = 0;
	
	public function __construct($size = 50)
	{
		$this->_size = $size;
	}
	
	
	public function add($entry)
	{
		$this->_log[] = $entry;
		while (count($this->_log) > $this->_size)
		{
			array_shift($this->_log);
		}
	
	}
	
	
	public function clear()
	{
		$this->_log = array();
	}
	
	
	public function dump()
	{
		return implode(PHP_EOL, $this->_log);
	}

}