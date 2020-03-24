<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_MailTransport extends Swift_Transport_MailTransport
{
	
	public function __construct($extraParams = '-f%s')
	{
		call_user_func_array(array($this, 'Swift_Transport_MailTransport::__construct'), Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.mail'));
		$this->setExtraParams($extraParams);
	}
	
	
	public static function newInstance($extraParams = '-f%s')
	{
		return new self($extraParams);
	}

}