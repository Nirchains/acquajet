<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_CharacterReaderFactory_SimpleCharacterReaderFactory implements Swift_CharacterReaderFactory
{
	private static $_map = array();
	private static $_loaded = array();
	
	public function __construct()
	{
		$this->init();
	}
	
	
	public function __wakeup()
	{
		$this->init();
	}
	
	
	public function init()
	{
		if (count(self::$_map) > 0)
		{
			return;
		}
		
		$prefix = 'Swift_CharacterReader_';
		$singleByte = array('class' => $prefix . 'GenericFixedWidthReader', 'constructor' => array(1));
		$doubleByte = array('class' => $prefix . 'GenericFixedWidthReader', 'constructor' => array(2));
		$fourBytes = array('class' => $prefix . 'GenericFixedWidthReader', 'constructor' => array(4));
		self::$_map['utf-?8'] = array('class' => $prefix . 'Utf8Reader', 'constructor' => array());
		self::$_map['(us-)?ascii'] = $singleByte;
		self::$_map['(iso|iec)-?8859-?[0-9]+'] = $singleByte;
		self::$_map['windows-?125[0-9]'] = $singleByte;
		self::$_map['cp-?[0-9]+'] = $singleByte;
		self::$_map['ansi'] = $singleByte;
		self::$_map['macintosh'] = $singleByte;
		self::$_map['koi-?7'] = $singleByte;
		self::$_map['koi-?8-?.+'] = $singleByte;
		self::$_map['mik'] = $singleByte;
		self::$_map['(cork|t1)'] = $singleByte;
		self::$_map['v?iscii'] = $singleByte;
		self::$_map['(ucs-?2|utf-?16)'] = $doubleByte;
		self::$_map['(ucs-?4|utf-?32)'] = $fourBytes;
		self::$_map['.*'] = $singleByte;
	}
	
	
	public function getReaderFor($charset)
	{
		$charset = trim(strtolower($charset));
		foreach (self::$_map as $pattern => $spec)
		{
			$re = '/^' . $pattern . '$/D';
			if (preg_match($re, $charset))
			{
				if (!array_key_exists($pattern, self::$_loaded))
				{
					$reflector = new ReflectionClass($spec['class']);
					if ($reflector->getConstructor())
					{
						$reader = $reflector->newInstanceArgs($spec['constructor']);
					}
					else
					{
						$reader = $reflector->newInstance();
					}
					
					self::$_loaded[$pattern] = $reader;
				}
				
				return self::$_loaded[$pattern];
			}
		
		}
	
	}

}