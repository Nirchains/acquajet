<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

abstract class Swift_ConfigurableSpool implements Swift_Spool
{
	private $_message_limit;
	private $_time_limit;
	
	public function setMessageLimit($limit)
	{
		$this->_message_limit = (int) $limit;
	}
	
	
	public function getMessageLimit()
	{
		return $this->_message_limit;
	}
	
	
	public function setTimeLimit($limit)
	{
		$this->_time_limit = (int) $limit;
	}
	
	
	public function getTimeLimit()
	{
		return $this->_time_limit;
	}

}