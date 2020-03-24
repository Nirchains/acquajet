<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_LoadBalancedTransport implements Swift_Transport
{
	private $_deadTransports = array();
	protected $_transports = array();
	protected $_lastUsedTransport = null;
	
	public function __construct()
	{
	}
	
	
	public function setTransports(array $transports)
	{
		$this->_transports = $transports;
		$this->_deadTransports = array();
	}
	
	
	public function getTransports()
	{
		return array_merge($this->_transports, $this->_deadTransports);
	}
	
	
	public function getLastUsedTransport()
	{
		return $this->_lastUsedTransport;
	}
	
	
	public function isStarted()
	{
		return count($this->_transports) > 0;
	}
	
	
	public function start()
	{
		$this->_transports = array_merge($this->_transports, $this->_deadTransports);
	}
	
	
	public function stop()
	{
		foreach ($this->_transports as $transport)
		{
			$transport->stop();
		}
	
	}
	
	
	public function send(Swift_Mime_Message $message, &$failedRecipients = null)
	{
		$maxTransports = count($this->_transports);
		$sent = 0;
		$this->_lastUsedTransport = null;
		for ($i = 0; $i < $maxTransports && ($transport = $this->_getNextTransport()); ++$i)
		{
			try
			{
				if (!$transport->isStarted())
				{
					$transport->start();
				}
				
				if ($sent = $transport->send($message, $failedRecipients))
				{
					$this->_lastUsedTransport = $transport;
					break;
				}
			
			}
			catch (Swift_TransportException $e)
			{
				$this->_killCurrentTransport();
			}
		
		}
		
		if (count($this->_transports) == 0)
		{
			throw new Swift_TransportException('All Transports in LoadBalancedTransport failed, or no Transports available');
		}
		
		return $sent;
	}
	
	
	public function registerPlugin(Swift_Events_EventListener $plugin)
	{
		foreach ($this->_transports as $transport)
		{
			$transport->registerPlugin($plugin);
		}
	
	}
	
	
	protected function _getNextTransport()
	{
		if ($next = array_shift($this->_transports))
		{
			$this->_transports[] = $next;
		}
		
		return $next;
	}
	
	
	protected function _killCurrentTransport()
	{
		if ($transport = array_pop($this->_transports))
		{
			try
			{
				$transport->stop();
			}
			catch (Exception $e)
			{
			}
			
			$this->_deadTransports[] = $transport;
		}
	
	}

}