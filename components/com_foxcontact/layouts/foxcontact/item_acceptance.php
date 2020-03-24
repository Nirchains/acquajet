<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
list($uid, $board, $current, $form) = FoxFormRender::listFormVariables('uid,board');
FoxHtmlElem::create('div')->attr('id', $current->getBoxId())->classes('fox-item fox-item-acceptance control-group')->classes($current->get('classes'))->classes($board->getItemDecorationClass($current->get('unique_id')))->append(FoxHtmlElem::fromFunction(function () use($current)
{
	$inner = FoxHtmlElem::create('div')->attr('style', 'border: 1px #aaaaaa solid; padding: 8px; border-radius: 4px;')->append(FoxHtmlElem::create('div')->attr('style', 'overflow: auto; width: 9999px; max-width: 100%; height: 150px;')->html($current->get('html')));
	switch ($current->get('alignment'))
	{
		case 'labels':
			return FoxHtmlElem::create('div')->classes('control-group')->append($inner);
		case 'fields':
			return FoxHtmlElem::create('div')->classes('control-group')->append(FoxFormRender::render('label_collapsed'))->append(FoxHtmlElem::create('div')->classes('controls')->attr('style', "{$current->getStyleWidth()}{$current->getStyleHeight()}")->append($inner));
		default:
			return null;
	}

}))->append(FoxHtmlElem::create('div')->classes('control-group')->append(FoxFormRender::render('label_collapsed'))->append(FoxHtmlElem::create('div')->classes('controls')->attr('style', "{$current->getStyleWidth()}{$current->getStyleHeight()}")->append(FoxHtmlElem::create('label')->classes('checkbox')->tooltip($current->get('tooltip'))->attr('for', $current->getItemId())->append(FoxHtmlElem::create('input')->attr('id', $current->getItemId())->attr('name', $current->getInputName())->attr('type', 'checkbox')->attr('value', JText::_('JYES'))->checked($current->getInputValue() === JText::_('JYES')))->append(FoxHtmlElem::fromFunction(function () use($current)
{
	if ($current->get('required'))
	{
		return FoxHtmlElem::create('span')->classes('required');
	}
	
	return null;
}))->append($current->get('checkbox_label'))->append(FoxHtmlElem::fromFunction(function () use($current, $board)
{
	if ($board->isValidated() && $board->isFieldInvalid($current->get('unique_id')))
	{
		return FoxHtmlElem::create('span')->classes('asterisk');
	}
	
	return null;
})))))->show();