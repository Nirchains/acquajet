<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
jimport('joomla.filesystem.folder');
jimport('foxcontact.html.elem');
jimport('foxcontact.html.template');
jimport('foxcontact.form.newsletter');
jimport('foxcontact.struct.manager');
jimport('foxcontact.html.resource');
jimport('foxcontact.html.util');
jimport('foxcontact.joomla.editor');
jimport('foxcontact.joomla.lang');

class JFormFieldVisualDesigner extends JFormField
{
	protected $type = 'VisualDesigner';
	
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		if (JFactory::getApplication()->input->get('option', '') === 'com_falang')
		{
			$msg = JText::_('COM_FOXCONTACT_ERR_COM_FALANG');
			$url = 'http://www.fox.ra.it/forum/22-how-to/5042-setup-a-multilanguage-form.html';
			$this->incompatibilityWarning($msg, $url);
			return '';
		}
		
		$current_template = JFactory::getApplication()->getTemplate();
		$config = JComponentHelper::getParams('com_foxcontact');
		if ($current_template !== 'isis' && !(bool) $config->get('skip_template_check', false))
		{
			$msg = JText::sprintf('COM_FOXCONTACT_ERR_ADMIN_TEMPLATE', $current_template);
			$url = 'http://www.fox.ra.it/forum/15-installation/77-joomla-compatibility-list.html';
			$this->incompatibilityWarning($msg, $url);
			return '';
		}
		
		$resources = FoxHtmlResource::NewInstance();
		$resources->Add('/administrator/components/com_foxcontact/css/designer', 'css');
		$resources->Add('/administrator/components/com_foxcontact/js/designer', 'js');
		FoxJoomlaEditor::init();
		JHtml::_('jquery.ui', array('core', 'sortable'));
		$current_language = JFactory::getLanguage()->getTag();
		$this->verifyLngJsFile($current_language);
		$resources->Add("/administrator/cache/foxcontact/{$current_language}.foxcontact", 'js');
		$document = JFactory::getDocument();
		$resources->ToDocument($document);
		$media_root = JPATH_ROOT . '/media/com_foxcontact';
		$cmp_root = JPATH_ROOT . '/components/com_foxcontact';
		FoxHtmlUtil::addMetaTagData('fox:submit:files', array('submit' => array('icons' => JFolder::files("{$media_root}/images/submit", '\\.png$'), 'images' => JFolder::files("{$media_root}/images/buttons/submit", '\\.png$')), 'reset' => array('icons' => JFolder::files("{$media_root}/images/reset", '\\.png$'), 'images' => JFolder::files("{$media_root}/images/buttons/reset", '\\.png$'))));
		FoxHtmlUtil::addMetaTagData('fox:captcha:fonts', JFolder::files("{$media_root}/fonts", '\\.ttf$'));
		FoxHtmlUtil::addMetaTagData('fox:form:stylesheets', JFolder::files("{$cmp_root}/css", '\\.css$'));
		FoxHtmlUtil::addMetaTagData('fox:newsletter:entries', FoxFormNewsletter::loadAll());
		return parent::setup($element, $value, $group);
	}
	
	
	protected function getLabel()
	{
		return '';
	}
	
	
	protected function getInput()
	{
		if (empty($this->value) || $this->value === '{}')
		{
			$this->value = $this->getDefaultValue((string) $this->element['default-scope']);
		}
		
		$this->value = json_encode(FoxStructManager::check($this->value));
		return FoxHtmlElem::create()->append(FoxHtmlElem::create('input')->attr('id', $this->id)->attr('name', $this->name)->attr('type', 'hidden')->attr('value', $this->value))->append(FoxHtmlElem::create('div')->attr('id', 'fvd-target-1')->classes('fvd-target')->attr('style', 'display: none;'))->append(FoxHtmlElem::create('div')->attr('id', 'fvd-target-2')->classes('fvd-target')->attr('style', 'display: none;'))->append(FoxHtmlElem::create('div')->classes('fvd-dark-layer'))->append(FoxHtmlElem::create('div')->attr('id', 'fvd-window-1')->classes('fvd-window')->attr('style', 'display: none;'))->append(FoxHtmlElem::create('div')->attr('id', 'fvd-designer')->classes('fvd-visual-designer')->attr('data-ref', $this->id))->conditional(JDEBUG, function ()
		{
			return FoxHtmlElem::create('pre')->attr('id', 'fvd-debug')->classes('fvd-debug')->attr('style', 'display: none;');
		})->render();
	}
	
	
	private function incompatibilityWarning($msg, $url)
	{
		JEventDispatcher::getInstance()->register('onAfterDispatch', function () use($msg, $url)
		{
			FoxJoomlaLang::load(true, true);
			$values = array('type' => 'warning', 'icon' => 'warning-2', 'title' => JText::_('WARNING'), 'message' => $msg, 'buttons' => array(array('class' => 'btn btn-primary', 'label' => JText::_('COM_FOXCONTACT_READ_MORE'), 'url' => $url)));
			$buffer = JLayoutHelper::render('alert', $values, JPATH_ADMINISTRATOR . '/components/com_foxcontact/layouts/fox');
			JFactory::getDocument()->setBuffer($buffer, 'component');
		});
		JEventDispatcher::getInstance()->register('onRenderModule', function ($module)
		{
			if ($module->module === 'mod_toolbar')
			{
				$toolbar = JToolbar::getInstance('com_foxcontact_edit_outside_toolbar');
				$button = '<button onclick="window.history.back();" class="btn btn-small"><span class="icon-back icon-32-back"></span>' . JText::_('JTOOLBAR_BACK') . '</button>';
				$toolbar->appendButton('Custom', $button);
				$module->content = $toolbar->render();
			}
		
		});
	}
	
	
	private function verifyLngJsFile($ln)
	{
		$cache_directory = JPATH_ADMINISTRATOR . '/cache/foxcontact';
		@mkdir($cache_directory, 511, true);
		if (!is_dir($cache_directory))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FOXCONTACT_CACHE_WRITE_ERROR'), 'error');
			return;
		}
		
		if (!is_file($htaccess = $cache_directory . '/.htaccess'))
		{
			@file_put_contents($htaccess, "<IfModule mod_rewrite.c>\nRewriteEngine Off\n</IfModule>");
		}
		
		$cache_file = $cache_directory . "/{$ln}.foxcontact.min.js";
		if ($this->isExpiredLngJsFile($cache_file, $ln))
		{
			$this->buildLngJsFile($cache_file);
		}
	
	}
	
	
	private function isExpiredLngJsFile($file_name, $ln)
	{
		$files = array(JPATH_ADMINISTRATOR . '/components/com_foxcontact/language/en-GB/en-GB.com_foxcontact.ini', JPATH_ADMINISTRATOR . "/components/com_foxcontact/language/{$ln}/{$ln}.com_foxcontact.ini", JPATH_ADMINISTRATOR . '/language/overrides/en-GB.override.ini', JPATH_ADMINISTRATOR . "/language/overrides/{$ln}.override.ini", JPATH_ROOT . '/components/com_foxcontact/language/en-GB/en-GB.com_foxcontact.ini', JPATH_ROOT . "/components/com_foxcontact/language/{$ln}/{$ln}.com_foxcontact.ini", JPATH_ROOT . '/language/overrides/en-GB.override.ini', JPATH_ROOT . "/language/overrides/{$ln}.override.ini");
		$reference = @filemtime($file_name) or $reference = 0;
		foreach ($files as $file)
		{
			if (@filemtime($file) > $reference)
			{
				return true;
			}
		
		}
		
		return false;
	}
	
	
	private function buildLngJsFile($file_name)
	{
		$result = array();
		FoxJoomlaLang::load(true, true);
		foreach ($this->getLngJsFileKeys() as $k)
		{
			$result[$k] = JText::_($k);
		}
		
		$result = json_encode($result);
		if (@file_put_contents($file_name, "fox.lang.init({$result});") === false)
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_FOXCONTACT_CACHE_WRITE_ERROR'), 'error');
		}
	
	}
	
	
	private function getLngJsFileKeys()
	{
		return array_merge($this->parse_ini_file('/administrator/components/com_foxcontact/language/en-GB/en-GB.com_foxcontact.ini'), $this->parse_ini_file('/components/com_foxcontact/language/en-GB/en-GB.com_foxcontact.ini'));
	}
	
	
	private function parse_ini_file($file_name)
	{
		$records = parse_ini_string(@file_get_contents(JPATH_ROOT . $file_name)) or $records = array();
		return array_keys($records);
	}
	
	
	private function getDefaultValue($scope)
	{
		switch (strtolower($scope))
		{
			case 'component':
				return require __DIR__ . '/design.component.php';
			case 'module':
				return require __DIR__ . '/design.module.php';
			default:
				return require __DIR__ . '/design.naked.php';
		}
	
	}

}