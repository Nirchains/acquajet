<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_Headers_OpenDKIMHeader implements Swift_Mime_Header
{
	private $_value;
	private $_fieldName;
	
	public function __construct($name)
	{
		$this->_fieldName = $name;
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
		$this->_value = $value;
	}
	
	
	public function getFieldBody()
	{
		return $this->_value;
	}
	
	
	public function toString()
	{
		return $this->_fieldName . ': ' . $this->_value;
	}
	
	
	public function getFieldName()
	{
		return $this->_fieldName;
	}
	
	
	public function setCharset($charset)
	{
	}

}