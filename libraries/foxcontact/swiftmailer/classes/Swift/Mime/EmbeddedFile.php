<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */

class Swift_Mime_EmbeddedFile extends Swift_Mime_Attachment
{
	
	public function __construct(Swift_Mime_HeaderSet $headers, Swift_Mime_ContentEncoder $encoder, Swift_KeyCache $cache, Swift_Mime_Grammar $grammar, $mimeTypes = array())
	{
		parent::__construct($headers, $encoder, $cache, $grammar, $mimeTypes);
		$this->setDisposition('inline');
		$this->setId($this->getId());
	}
	
	
	public function getNestingLevel()
	{
		return self::LEVEL_RELATED;
	}

}