<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_StreamFilters_StringReplacementFilterFactory implements Swift_ReplacementFilterFactory
{
	private $_filters = array();
	
	public function createFilter($search, $replace)
	{
		if (!isset($this->_filters[$search][$replace]))
		{
			if (!isset($this->_filters[$search]))
			{
				$this->_filters[$search] = array();
			}
			
			if (!isset($this->_filters[$search][$replace]))
			{
				$this->_filters[$search][$replace] = array();
			}
			
			$this->_filters[$search][$replace] = new Swift_StreamFilters_StringReplacementFilter($search, $replace);
		}
		
		return $this->_filters[$search][$replace];
	}

}