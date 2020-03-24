<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_Headers_MailboxHeader extends Swift_Mime_Headers_AbstractHeader
{
	private $_mailboxes = array();
	
	public function __construct($name, Swift_Mime_HeaderEncoder $encoder, Swift_Mime_Grammar $grammar)
	{
		$this->setFieldName($name);
		$this->setEncoder($encoder);
		parent::__construct($grammar);
	}
	
	
	public function getFieldType()
	{
		return self::TYPE_MAILBOX;
	}
	
	
	public function setFieldBodyModel($model)
	{
		$this->setNameAddresses($model);
	}
	
	
	public function getFieldBodyModel()
	{
		return $this->getNameAddresses();
	}
	
	
	public function setNameAddresses($mailboxes)
	{
		$this->_mailboxes = $this->normalizeMailboxes((array) $mailboxes);
		$this->setCachedValue(null);
	}
	
	
	public function getNameAddressStrings()
	{
		return $this->_createNameAddressStrings($this->getNameAddresses());
	}
	
	
	public function getNameAddresses()
	{
		return $this->_mailboxes;
	}
	
	
	public function setAddresses($addresses)
	{
		$this->setNameAddresses(array_values((array) $addresses));
	}
	
	
	public function getAddresses()
	{
		return array_keys($this->_mailboxes);
	}
	
	
	public function removeAddresses($addresses)
	{
		$this->setCachedValue(null);
		foreach ((array) $addresses as $address)
		{
			unset($this->_mailboxes[$address]);
		}
	
	}
	
	
	public function getFieldBody()
	{
		if (null === $this->getCachedValue())
		{
			$this->setCachedValue($this->createMailboxListString($this->_mailboxes));
		}
		
		return $this->getCachedValue();
	}
	
	
	protected function normalizeMailboxes(array $mailboxes)
	{
		$actualMailboxes = array();
		foreach ($mailboxes as $key => $value)
		{
			if (is_string($key))
			{
				$address = $key;
				$name = $value;
			}
			else
			{
				$address = $value;
				$name = null;
			}
			
			$this->_assertValidAddress($address);
			$actualMailboxes[$address] = $name;
		}
		
		return $actualMailboxes;
	}
	
	
	protected function createDisplayNameString($displayName, $shorten = false)
	{
		return $this->createPhrase($this, $displayName, $this->getCharset(), $this->getEncoder(), $shorten);
	}
	
	
	protected function createMailboxListString(array $mailboxes)
	{
		return implode(', ', $this->_createNameAddressStrings($mailboxes));
	}
	
	
	protected function tokenNeedsEncoding($token)
	{
		return preg_match('/[()<>\\[\\]:;@\\,."]/', $token) || parent::tokenNeedsEncoding($token);
	}
	
	
	private function _createNameAddressStrings(array $mailboxes)
	{
		$strings = array();
		foreach ($mailboxes as $email => $name)
		{
			$mailboxStr = $email;
			if (null !== $name)
			{
				$nameStr = $this->createDisplayNameString($name, empty($strings));
				$mailboxStr = $nameStr . ' <' . $mailboxStr . '>';
			}
			
			$strings[] = $mailboxStr;
		}
		
		return $strings;
	}
	
	
	private function _assertValidAddress($address)
	{
		if (!preg_match('/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D', $address))
		{
			throw new Swift_RfcComplianceException('Address in mailbox given [' . $address . '] does not comply with RFC 2822, 3.6.2.');
		}
	
	}

}