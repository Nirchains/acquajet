<?php defined('_JEXEC') or die;
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) Fox Labs, all rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
interface Swift_Mime_Header
{
	const TYPE_TEXT = 2;
	const TYPE_PARAMETERIZED = 6;
	const TYPE_MAILBOX = 8;
	const TYPE_DATE = 16;
	const TYPE_ID = 32;
	const TYPE_PATH = 64;
	
	public function getFieldType();
	
	public function setFieldBodyModel($model);
	
	public function setCharset($charset);
	
	public function getFieldBodyModel();
	
	public function getFieldName();
	
	public function getFieldBody();
	
	public function toString();
}