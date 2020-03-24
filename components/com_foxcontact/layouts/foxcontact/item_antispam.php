<?php defined('_JEXEC') or die(file_get_contents('index.html'));
/**
 * @package   Fox Contact for Joomla
 * @copyright Copyright (c) 2010 - 2015 Demis Palma. All rights reserved.
 * @license   Distributed under the terms of the GNU General Public License GNU/GPL v3 http://www.gnu.org/licenses/gpl-3.0.html
 * @see       Documentation: http://www.fox.ra.it/forum/2-documentation.html
 */
list($uid, $board, $current, $form) = FoxFormRender::listFormVariables('uid,board');
FoxHtmlElem::create('div')->attr('id', "fox-{$current->get('uid')}-message-box")->attr('style', 'display: none !important; position: absolute; left: -9000px; top: -9000px;')->append(FoxFormRender::render('label'))->append(FoxHtmlElem::create('div')->classes('controls')->append(FoxHtmlElem::create('textarea')->attr('id', "fox-{$current->get('uid')}-message")->attr('name', "fox_form[{$current->get('uid')}][message]")->text($current->getHoneypotInputValue()))->append(FoxHtmlElem::create('input')->attr('type', 'hidden')->attr('id', "fox-{$current->get('uid')}-action")->attr('name', "fox_form[{$current->get('uid')}][action]")->attr('value', $current->getInteractionInputValue())->classes('action')))->show();