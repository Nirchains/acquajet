<?php
/**
*	@package	Ajax Intro Articles
*	@copyright	Copyright (C) 2018 Aplikko. All rights reserved.
*	@license	GNU/GPL version 2, or later
*	@website:	http://www.aplikko.com
*/

defined( '_JEXEC' ) or die( 'Restricted access' );

$start = 0;
$limit = $params->get('count', 3);
$ajaxlimit = $params->get('ajax_limit', 3);
//$ajaxRequest = false; 

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';
require_once JPATH_SITE . '/components/com_content/helpers/route.php';
		
if( JRequest::getInt('moduleID', 0) > 0 ){
	$start = JRequest::getInt('start');
	$limit = JRequest::getInt('limit', $ajaxlimit);
	//$ajaxRequest = true;
}

$list = ModAjaxIntroArticlesHelper::getList($params, $start, $limit);
//$ajax_items = ModAjaxArticlesHelper::getAjaxlimit($params);
$total = ModAjaxIntroArticlesHelper::getTotal($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_ajax_intro_articles', $params->get('layout', 'default'));
