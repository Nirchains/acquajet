<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_ReporterPlugin implements Swift_Events_SendListener
{
	private $_reporter;
	
	public function __construct(Swift_Plugins_Reporter $reporter)
	{
		$this->_reporter = $reporter;
	}
	
	
	public function beforeSendPerformed(Swift_Events_SendEvent $evt)
	{
	}
	
	
	public function sendPerformed(Swift_Events_SendEvent $evt)
	{
		$message = $evt->getMessage();
		$failures = array_flip($evt->getFailedRecipients());
		foreach ((array) $message->getTo() as $address => $null)
		{
			$this->_reporter->notify($message, $address, array_key_exists($address, $failures) ? Swift_Plugins_Reporter::RESULT_FAIL : Swift_Plugins_Reporter::RESULT_PASS);
		}
		
		foreach ((array) $message->getCc() as $address => $null)
		{
			$this->_reporter->notify($message, $address, array_key_exists($address, $failures) ? Swift_Plugins_Reporter::RESULT_FAIL : Swift_Plugins_Reporter::RESULT_PASS);
		}
		
		foreach ((array) $message->getBcc() as $address => $null)
		{
			$this->_reporter->notify($message, $address, array_key_exists($address, $failures) ? Swift_Plugins_Reporter::RESULT_FAIL : Swift_Plugins_Reporter::RESULT_PASS);
		}
	
	}

}