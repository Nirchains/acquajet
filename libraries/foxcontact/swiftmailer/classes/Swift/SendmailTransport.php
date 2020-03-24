<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_SendmailTransport extends Swift_Transport_SendmailTransport
{
	
	public function __construct($command = '/usr/sbin/sendmail -bs')
	{
		call_user_func_array(array($this, 'Swift_Transport_SendmailTransport::__construct'), Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.sendmail'));
		$this->setCommand($command);
	}
	
	
	public static function newInstance($command = '/usr/sbin/sendmail -bs')
	{
		return new self($command);
	}

}