<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_Esmtp_Auth_LoginAuthenticator implements Swift_Transport_Esmtp_Authenticator
{
	
	public function getAuthKeyword()
	{
		return 'LOGIN';
	}
	
	
	public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
	{
		try
		{
			$agent->executeCommand("AUTH LOGIN\r\n", array(334));
			$agent->executeCommand(sprintf("%s\r\n", base64_encode($username)), array(334));
			$agent->executeCommand(sprintf("%s\r\n", base64_encode($password)), array(235));
			return true;
		}
		catch (Swift_TransportException $e)
		{
			$agent->executeCommand("RSET\r\n", array(250));
			return false;
		}
	
	}

}