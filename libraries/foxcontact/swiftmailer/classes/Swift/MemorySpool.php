<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_MemorySpool implements Swift_Spool
{
	protected $messages = array();
	private $flushRetries = 3;
	
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
	
	
	public function setFlushRetries($retries)
	{
		$this->flushRetries = $retries;
	}
	
	
	public function queueMessage(Swift_Mime_Message $message)
	{
		$this->messages[] = clone $message;
		return true;
	}
	
	
	public function flushQueue(Swift_Transport $transport, &$failedRecipients = null)
	{
		if (!$this->messages)
		{
			return 0;
		}
		
		if (!$transport->isStarted())
		{
			$transport->start();
		}
		
		$count = 0;
		$retries = $this->flushRetries;
		while ($retries--)
		{
			try
			{
				while ($message = array_pop($this->messages))
				{
					$count += $transport->send($message, $failedRecipients);
				}
			
			}
			catch (Swift_TransportException $exception)
			{
				if ($retries)
				{
					array_unshift($this->messages, $message);
					usleep(500000);
				}
				else
				{
					throw $exception;
				}
			
			}
		
		}
		
		return $count;
	}

}