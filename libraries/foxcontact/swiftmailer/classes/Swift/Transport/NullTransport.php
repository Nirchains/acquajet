<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_NullTransport implements Swift_Transport
{
	private $_eventDispatcher;
	
	public function __construct(Swift_Events_EventDispatcher $eventDispatcher)
	{
		$this->_eventDispatcher = $eventDispatcher;
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
		
		if ($evt)
		{
			$evt->setResult(Swift_Events_SendEvent::RESULT_SUCCESS);
			$this->_eventDispatcher->dispatchEvent($evt, 'sendPerformed');
		}
		
		$count = count((array) $message->getTo()) + count((array) $message->getCc()) + count((array) $message->getBcc());
		return $count;
	}
	
	
	public function registerPlugin(Swift_Events_EventListener $plugin)
	{
		$this->_eventDispatcher->bindEventListener($plugin);
	}

}