<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_FailoverTransport extends Swift_Transport_LoadBalancedTransport
{
	private $_currentTransport;
	
	public function __construct()
	{
		parent::__construct();
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
					return $sent;
				}
			
			}
			catch (Swift_TransportException $e)
			{
				$this->_killCurrentTransport();
			}
		
		}
		
		if (count($this->_transports) == 0)
		{
			throw new Swift_TransportException('All Transports in FailoverTransport failed, or no Transports available');
		}
		
		return $sent;
	}
	
	
	protected function _getNextTransport()
	{
		if (!isset($this->_currentTransport))
		{
			$this->_currentTransport = parent::_getNextTransport();
		}
		
		return $this->_currentTransport;
	}
	
	
	protected function _killCurrentTransport()
	{
		$this->_currentTransport = null;
		parent::_killCurrentTransport();
	}

}