<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_Headers_DateHeader extends Swift_Mime_Headers_AbstractHeader
{
	private $_timestamp;
	
	public function __construct($name, Swift_Mime_Grammar $grammar)
	{
		$this->setFieldName($name);
		parent::__construct($grammar);
	}
	
	
	public function getFieldType()
	{
		return self::TYPE_DATE;
	}
	
	
	public function setFieldBodyModel($model)
	{
		$this->setTimestamp($model);
	}
	
	
	public function getFieldBodyModel()
	{
		return $this->getTimestamp();
	}
	
	
	public function getTimestamp()
	{
		return $this->_timestamp;
	}
	
	
	public function setTimestamp($timestamp)
	{
		if (null !== $timestamp)
		{
			$timestamp = (int) $timestamp;
		}
		
		$this->clearCachedValueIf($this->_timestamp != $timestamp);
		$this->_timestamp = $timestamp;
	}
	
	
	public function getFieldBody()
	{
		if (!$this->getCachedValue())
		{
			if (isset($this->_timestamp))
			{
				$this->setCachedValue(date('r', $this->_timestamp));
			}
		
		}
		
		return $this->getCachedValue();
	}

}