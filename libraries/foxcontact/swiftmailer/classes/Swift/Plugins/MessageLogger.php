<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_MessageLogger implements Swift_Events_SendListener
{
	private $messages;
	
	public function __construct()
	{
		$this->messages = array();
	}
	
	
	public function getMessages()
	{
		return $this->messages;
	}
	
	
	public function countMessages()
	{
		return count($this->messages);
	}
	
	
	public function clear()
	{
		$this->messages = array();
	}
	
	
	public function beforeSendPerformed(Swift_Events_SendEvent $evt)
	{
		$this->messages[] = clone $evt->getMessage();
	}
	
	
	public function sendPerformed(Swift_Events_SendEvent $evt)
	{
	}

}