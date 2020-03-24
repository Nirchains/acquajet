<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_Esmtp_Auth_PlainAuthenticator implements Swift_Transport_Esmtp_Authenticator
{
	
	public function getAuthKeyword()
	{
		return 'PLAIN';
	}
	
	
	public function authenticate(Swift_Transport_SmtpAgent $agent, $username, $password)
	{
		try
		{
			$message = base64_encode($username . chr(0) . $username . chr(0) . $password);
			$agent->executeCommand(sprintf("AUTH PLAIN %s\r\n", $message), array(235));
			return true;
		}
		catch (Swift_TransportException $e)
		{
			$agent->executeCommand("RSET\r\n", array(250));
			return false;
		}
	
	}

}