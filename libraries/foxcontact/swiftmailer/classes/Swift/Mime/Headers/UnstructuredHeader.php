<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_Headers_UnstructuredHeader extends Swift_Mime_Headers_AbstractHeader
{
	private $_value;
	
	public function __construct($name, Swift_Mime_HeaderEncoder $encoder, Swift_Mime_Grammar $grammar)
	{
		$this->setFieldName($name);
		$this->setEncoder($encoder);
		parent::__construct($grammar);
	}
	
	
	public function getFieldType()
	{
		return self::TYPE_TEXT;
	}
	
	
	public function setFieldBodyModel($model)
	{
		$this->setValue($model);
	}
	
	
	public function getFieldBodyModel()
	{
		return $this->getValue();
	}
	
	
	public function getValue()
	{
		return $this->_value;
	}
	
	
	public function setValue($value)
	{
		$this->clearCachedValueIf($this->_value != $value);
		$this->_value = $value;
	}
	
	
	public function getFieldBody()
	{
		if (!$this->getCachedValue())
		{
			$this->setCachedValue($this->encodeWords($this, $this->_value));
		}
		
		return $this->getCachedValue();
	}

}