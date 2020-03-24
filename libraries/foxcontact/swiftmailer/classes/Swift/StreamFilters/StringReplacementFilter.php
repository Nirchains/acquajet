<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_StreamFilters_StringReplacementFilter implements Swift_StreamFilter
{
	private $_search;
	private $_replace;
	
	public function __construct($search, $replace)
	{
		$this->_search = $search;
		$this->_replace = $replace;
	}
	
	
	public function shouldBuffer($buffer)
	{
		if ('' === $buffer)
		{
			return false;
		}
		
		$endOfBuffer = substr($buffer, -1);
		foreach ((array) $this->_search as $needle)
		{
			if (false !== strpos($needle, $endOfBuffer))
			{
				return true;
			}
		
		}
		
		return false;
	}
	
	
	public function filter($buffer)
	{
		return str_replace($this->_search, $this->_replace, $buffer);
	}

}