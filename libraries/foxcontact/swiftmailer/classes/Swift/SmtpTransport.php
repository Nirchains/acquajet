<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_SmtpTransport extends Swift_Transport_EsmtpTransport
{
	
	public function __construct($host = 'localhost', $port = 25, $security = null)
	{
		call_user_func_array(array($this, 'Swift_Transport_EsmtpTransport::__construct'), Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.smtp'));
		$this->setHost($host);
		$this->setPort($port);
		$this->setEncryption($security);
	}
	
	
	public static function newInstance($host = 'localhost', $port = 25, $security = null)
	{
		return new self($host, $port, $security);
	}

}