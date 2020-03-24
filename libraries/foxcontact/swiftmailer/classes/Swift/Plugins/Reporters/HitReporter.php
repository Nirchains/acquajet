<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_Reporters_HitReporter implements Swift_Plugins_Reporter
{
	private $_failures = array();
	private $_failures_cache = array();
	
	public function notify(Swift_Mime_Message $message, $address, $result)
	{
		if (self::RESULT_FAIL == $result && !isset($this->_failures_cache[$address]))
		{
			$this->_failures[] = $address;
			$this->_failures_cache[$address] = true;
		}
	
	}
	
	
	public function getFailedRecipients()
	{
		return $this->_failures;
	}
	
	
	public function clear()
	{
		$this->_failures = $this->_failures_cache = array();
	}

}