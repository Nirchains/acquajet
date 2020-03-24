<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.html.util');
jimport('foxcontact.html.resource');

class JFormFieldVersion extends JFormField
{
	protected $type = 'Version';
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$joomla_version = new JVersion();
		FoxHtmlUtil::addMetaTagData('joomla:version', $joomla_version->RELEASE);
		if (version_compare($joomla_version->RELEASE, '3.7', '<'))
		{
			FoxHtmlResource::NewInstance()->Add('/administrator/components/com_foxcontact/css/3-7', 'css')->ToDocument(JFactory::getDocument());
		}
		
		return true;
	}
	
	
	protected function getInput()
	{
		return '';
	}
	
	
	public function renderField($options = array())
	{
		return '';
	}

}