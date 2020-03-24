<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mailer_ArrayRecipientIterator implements Swift_Mailer_RecipientIterator
{
	private $_recipients = array();
	
	public function __construct(array $recipients)
	{
		$this->_recipients = $recipients;
	}
	
	
	public function hasNext()
	{
		return !empty($this->_recipients);
	}
	
	
	public function nextRecipient()
	{
		return array_splice($this->_recipients, 0, 1);
	}

}