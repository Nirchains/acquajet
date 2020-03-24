<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_Loggers_EchoLogger implements Swift_Plugins_Logger
{
	private $_isHtml;
	
	public function __construct($isHtml = true)
	{
		$this->_isHtml = $isHtml;
	}
	
	
	public function add($entry)
	{
		if ($this->_isHtml)
		{
			printf('%s%s%s', htmlspecialchars($entry, ENT_QUOTES), '<br />', PHP_EOL);
		}
		else
		{
			printf('%s%s', $entry, PHP_EOL);
		}
	
	}
	
	
	public function clear()
	{
	}
	
	
	public function dump()
	{
	}

}