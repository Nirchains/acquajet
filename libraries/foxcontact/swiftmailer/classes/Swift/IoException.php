<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_IoException extends Swift_SwiftException
{
	
	public function __construct($message, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

}