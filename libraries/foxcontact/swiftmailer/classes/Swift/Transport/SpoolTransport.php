<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_SpoolTransport implements Swift_Transport
{
	private $_spool;
	private $_eventDispatcher;
	
	public function __construct(Swift_Events_EventDispatcher $eventDispatcher, Swift_Spool $spool = null)
	{
		$this->_eventDispatcher = $eventDispatcher;
		$this->_spool = $spool;
	}
	
	
	public function setSpool(Swift_Spool $spool)
	{
		$this->_spool = $spool;
		return $this;
	}
	
	
	public function getSpool()
	{
		return $this->_spool;
	}
	
	
	public function isStarted()
	{
		return true;
	}
	
	
	public function start()
	{
	}
	
	
	public function stop()
	{
	}
	
	
	public function send(Swift_Mime_Message $message, &$failedRecipients = null)
	{
		if ($evt = $this->_eventDispatcher->createSendEvent($this, $message))
		{
			$this->_eventDispatcher->dispatchEvent($evt, 'beforeSendPerformed');
			if ($evt->bubbleCancelled())
			{
				return 0;
			}
		
		}
		
		$success = $this->_spool->queueMessage($message);
		if ($evt)
		{
			$evt->setResult($success ? Swift_Events_SendEvent::RESULT_SPOOLED : Swift_Events_SendEvent::RESULT_FAILED);
			$this->_eventDispatcher->dispatchEvent($evt, 'sendPerformed');
		}
		
		return 1;
	}
	
	
	public function registerPlugin(Swift_Events_EventListener $plugin)
	{
		$this->_eventDispatcher->bindEventListener($plugin);
	}

}