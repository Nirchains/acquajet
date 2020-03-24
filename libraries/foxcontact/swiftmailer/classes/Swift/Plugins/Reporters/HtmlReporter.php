<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Plugins_Reporters_HtmlReporter implements Swift_Plugins_Reporter
{
	
	public function notify(Swift_Mime_Message $message, $address, $result)
	{
		if (self::RESULT_PASS == $result)
		{
			echo '<div style="color: #fff; background: #006600; padding: 2px; margin: 2px;">' . PHP_EOL;
			echo 'PASS ' . $address . PHP_EOL;
			echo '</div>' . PHP_EOL;
			flush();
		}
		else
		{
			echo '<div style="color: #fff; background: #880000; padding: 2px; margin: 2px;">' . PHP_EOL;
			echo 'FAIL ' . $address . PHP_EOL;
			echo '</div>' . PHP_EOL;
			flush();
		}
	
	}

}