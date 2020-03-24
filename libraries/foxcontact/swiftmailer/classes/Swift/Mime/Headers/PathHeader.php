<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_Headers_PathHeader extends Swift_Mime_Headers_AbstractHeader
{
	private $_address;
	
	public function __construct($name, Swift_Mime_Grammar $grammar)
	{
		$this->setFieldName($name);
		parent::__construct($grammar);
	}
	
	
	public function getFieldType()
	{
		return self::TYPE_PATH;
	}
	
	
	public function setFieldBodyModel($model)
	{
		$this->setAddress($model);
	}
	
	
	public function getFieldBodyModel()
	{
		return $this->getAddress();
	}
	
	
	public function setAddress($address)
	{
		if (null === $address)
		{
			$this->_address = null;
		}
		elseif ('' == $address)
		{
			$this->_address = '';
		}
		else
		{
			$this->_assertValidAddress($address);
			$this->_address = $address;
		}
		
		$this->setCachedValue(null);
	}
	
	
	public function getAddress()
	{
		return $this->_address;
	}
	
	
	public function getFieldBody()
	{
		if (!$this->getCachedValue())
		{
			if (isset($this->_address))
			{
				$this->setCachedValue('<' . $this->_address . '>');
			}
		
		}
		
		return $this->getCachedValue();
	}
	
	
	private function _assertValidAddress($address)
	{
		if (!preg_match('/^' . $this->getGrammar()->getDefinition('addr-spec') . '$/D', $address))
		{
			throw new Swift_RfcComplianceException('Address set in PathHeader does not comply with addr-spec of RFC 2822.');
		}
	
	}

}