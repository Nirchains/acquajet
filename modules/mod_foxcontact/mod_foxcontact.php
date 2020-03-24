<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('foxcontact.joomla.lang');
jimport('foxcontact.form.model');
jimport('foxcontact.form.render');
if (isset($GLOBALS['foxcontact_mid_' . $module->id]))
{
	return;
}
else
{
	$GLOBALS['foxcontact_mid_' . $module->id] = true;
}

if (!isset($scope) && JFactory::getConfig()->get('caching') === '2')
{
	JFactory::getCache('com_modules', '')->cache->setCaching(false);
}

if (isset($scope))
{
	$content_managers = array('com_content' => 'ContentViewArticle', 'com_k2' => 'K2ViewItem');
	foreach ($content_managers as $content_manager => $view)
	{
		if ($scope === $content_manager)
		{
			$cache = @JFactory::getCache($content_manager, 'view');
			$cache->setCaching(false);
			if (version_compare(JVERSION, '3.7.0', '='))
			{
				$id = md5(serialize(array(JCache::makeId(), $view, 'display')));
				$cache->remove($id);
			}
			
			echo '<!--{emailcloak=off}-->';
		}
	
	}

}

$body = JFactory::getApplication()->getBody();
if (!empty($body))
{
	echo JText::_('COM_FOXCONTACT_ADDITIONAL_SETTINGS_REQUIRED') . ' <a href="http://www.fox.ra.it/forum/22-how-to/10274-nested-modules.html">' . JText::_('COM_FOXCONTACT_READ_MORE') . '</a>';
	return;
}

FoxJoomlaLang::load(true, false);
$form = FoxFormModel::getFormFromModule($module->id, $params);
FoxFormRender::start('form', $form);