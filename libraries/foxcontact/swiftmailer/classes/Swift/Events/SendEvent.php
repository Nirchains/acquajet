<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Events_SendEvent extends Swift_Events_EventObject
{
	const RESULT_PENDING = 1;
	const RESULT_SPOOLED = 17;
	const RESULT_SUCCESS = 16;
	const RESULT_TENTATIVE = 256;
	const RESULT_FAILED = 4096;
	private $_message;
	private $_failedRecipients = array();
	private $_result;
	
	public function __construct(Swift_Transport $source, Swift_Mime_Message $message)
	{
		parent::__construct($source);
		$this->_message = $message;
		$this->_result = self::RESULT_PENDING;
	}
	
	
	public function getTransport()
	{
		return $this->getSource();
	}
	
	
	public function getMessage()
	{
		return $this->_message;
	}
	
	
	public function setFailedRecipients($recipients)
	{
		$this->_failedRecipients = $recipients;
	}
	
	
	public function getFailedRecipients()
	{
		return $this->_failedRecipients;
	}
	
	
	public function setResult($result)
	{
		$this->_result = $result;
	}
	
	
	public function getResult()
	{
		return $this->_result;
	}

}