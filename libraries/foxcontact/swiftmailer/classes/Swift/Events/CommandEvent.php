<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Events_CommandEvent extends Swift_Events_EventObject
{
	private $_command;
	private $_successCodes = array();
	
	public function __construct(Swift_Transport $source, $command, $successCodes = array())
	{
		parent::__construct($source);
		$this->_command = $command;
		$this->_successCodes = $successCodes;
	}
	
	
	public function getCommand()
	{
		return $this->_command;
	}
	
	
	public function getSuccessCodes()
	{
		return $this->_successCodes;
	}

}