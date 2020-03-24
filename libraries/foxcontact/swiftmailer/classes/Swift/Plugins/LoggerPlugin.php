<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_LoggerPlugin implements Swift_Events_CommandListener, Swift_Events_ResponseListener, Swift_Events_TransportChangeListener, Swift_Events_TransportExceptionListener, Swift_Plugins_Logger
{
	private $_logger;
	
	public function __construct(Swift_Plugins_Logger $logger)
	{
		$this->_logger = $logger;
	}
	
	
	public function add($entry)
	{
		$this->_logger->add($entry);
	}
	
	
	public function clear()
	{
		$this->_logger->clear();
	}
	
	
	public function dump()
	{
		return $this->_logger->dump();
	}
	
	
	public function commandSent(Swift_Events_CommandEvent $evt)
	{
		$command = $evt->getCommand();
		$this->_logger->add(sprintf('>> %s', $command));
	}
	
	
	public function responseReceived(Swift_Events_ResponseEvent $evt)
	{
		$response = $evt->getResponse();
		$this->_logger->add(sprintf('<< %s', $response));
	}
	
	
	public function beforeTransportStarted(Swift_Events_TransportChangeEvent $evt)
	{
		$transportName = get_class($evt->getSource());
		$this->_logger->add(sprintf('++ Starting %s', $transportName));
	}
	
	
	public function transportStarted(Swift_Events_TransportChangeEvent $evt)
	{
		$transportName = get_class($evt->getSource());
		$this->_logger->add(sprintf('++ %s started', $transportName));
	}
	
	
	public function beforeTransportStopped(Swift_Events_TransportChangeEvent $evt)
	{
		$transportName = get_class($evt->getSource());
		$this->_logger->add(sprintf('++ Stopping %s', $transportName));
	}
	
	
	public function transportStopped(Swift_Events_TransportChangeEvent $evt)
	{
		$transportName = get_class($evt->getSource());
		$this->_logger->add(sprintf('++ %s stopped', $transportName));
	}
	
	
	public function exceptionThrown(Swift_Events_TransportExceptionEvent $evt)
	{
		$e = $evt->getException();
		$message = $e->getMessage();
		$code = $e->getCode();
		$this->_logger->add(sprintf('!! %s (code: %s)', $message, $code));
		$message .= PHP_EOL;
		$message .= 'Log data:' . PHP_EOL;
		$message .= $this->_logger->dump();
		$evt->cancelBubble();
		throw new Swift_TransportException($message, $code, $e->getPrevious());
	}

}