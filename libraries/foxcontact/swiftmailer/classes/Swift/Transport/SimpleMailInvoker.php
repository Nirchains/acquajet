<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Transport_SimpleMailInvoker implements Swift_Transport_MailInvoker
{
	
	public function mail($to, $subject, $body, $headers = null, $extraParams = null)
	{
		if (!ini_get('safe_mode'))
		{
			return @mail($to, $subject, $body, $headers, $extraParams);
		}
		
		return @mail($to, $subject, $body, $headers);
	}

}