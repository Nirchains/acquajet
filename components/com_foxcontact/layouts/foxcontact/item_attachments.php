<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
list($uid, $board, $current, $form) = FoxFormRender::listFormVariables('uid,board');
FoxHtmlElem::create()->append(FoxHtmlElem::create('div')->attr('id', $current->getBoxId())->attr('data-upload-btn', "fox-{$uid}-upload-btn")->attr('data-upload-lst', "fox-{$uid}-upload-lst")->attr('data-upload-url', JRoute::_("index.php?option=com_foxcontact&task=uploader.receive&uid={$uid}", false))->attr('data-upload-max-size', $current->getMaxFileSize())->classes('fox-item fox-item-attachments control-group')->classes($current->get('classes'))->classes($board->getItemDecorationClass($current->get('unique_id')))->append(FoxFormRender::render('label'))->append(FoxHtmlElem::create('div')->classes('controls')->attr('style', "{$current->getStyleWidth()}{$current->getStyleHeight()}")->conditional($form->getDesign()->get('option.label.position') === 'inside' && !$current->isEmpty('label'), function () use($current)
{
	return FoxHtmlElem::create('div')->append(FoxHtmlElem::create('label')->attr('for', $current->getLabelForId())->classes('fox-label-inside-no-placeholder')->tooltip($current->get('tooltip'))->html($current->get('label'))->conditional($current->get('required'), function ()
	{
		return FoxHtmlElem::create('span')->classes('required');
	}));
})->append(FoxHtmlElem::create('div')->classes('fox-item-attachments-btn-cnt')->append(FoxHtmlElem::create('div')->attr('id', "fox-{$uid}-upload-btn")->attr('data-input-id', $current->getItemId())->append(FoxHtmlElem::create('input')->attr('type', 'file')->attr('id', $current->getItemId())->classes('hide'))->append(FoxHtmlElem::create('div')->classes('qq-upload-button btn')->append(FoxHtmlElem::create('span')->classes('qq-upload-button-caption')->text(JText::_('COM_FOXCONTACT_BROWSE_FILES')))))->conditional($board->isValidated() && $board->isFieldInvalid($current->get('unique_id')), function ()
{
	return FoxHtmlElem::create('span')->classes('asterisk');
}))->append(FoxHtmlElem::create('span')->classes('help-block')->text($current->getHelp()))->append(FoxHtmlElem::create('noscript')->append(FoxHtmlElem::create('span')->text(JText::_('COM_FOXCONTACT_JAVASCRIPT_REQUIRED'))))))->append(FoxHtmlElem::create('div')->attr('id', "{$current->getBoxId()}-upload-lst")->classes('fox-item fox-item-attachments-upload-lst control-group')->append(FoxHtmlElem::create('div')->classes('controls')->append(FoxHtmlElem::create('ul')->attr('id', "fox-{$uid}-upload-lst")->classes('qq-upload-list')->appends($current->getFilesForRender(), function ($source, $index) use($uid, $current)
{
	return FoxHtmlElem::create('li')->classes('qq-upload-success')->append(FoxHtmlElem::create('span')->classes('qq-upload-file')->text($source['realname']))->append(FoxHtmlElem::create('span')->classes('qq-upload-size')->attr('style', 'display: inline-block;')->text($current->getHumanReadable($source['size'])))->append(FoxHtmlElem::create('span')->classes('qq-upload-success-text')->text(JText::_('COM_FOXCONTACT_SUCCESS')))->append(FoxHtmlElem::create('span')->classes('qq-upload-remove')->text(JText::_('COM_FOXCONTACT_REMOVE_ALT'))->attr('data-file-idx', $index)->attr('data-file-url', JRoute::_("index.php?option=com_foxcontact&task=uploader.receive&uid={$uid}", false)));
}))))->show();