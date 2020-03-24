<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Events_EventObject implements Swift_Events_Event
{
	private $_source;
	private $_bubbleCancelled = false;
	
	public function __construct($source)
	{
		$this->_source = $source;
	}
	
	
	public function getSource()
	{
		return $this->_source;
	}
	
	
	public function cancelBubble($cancel = true)
	{
		$this->_bubbleCancelled = $cancel;
	}
	
	
	public function bubbleCancelled()
	{
		return $this->_bubbleCancelled;
	}

}