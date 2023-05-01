<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

pluginsLoadConfig();
LoadPluginLang('advice', 'config', '', '', '#');

switch ($_REQUEST['action']) {
	case 'about':			about();		break;
	default: main();
}

function about()
{global $twig, $lang, $breadcrumb;
	$tpath = locatePluginTemplates(array('main', 'about'), 'advice', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-bullhorn btn-position"></i><span class="text-semibold">'.$lang['advice']['advice'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=advice' => '<i class="fa fa-bullhorn btn-position"></i>'.$lang['advice']['advice'].'',  '<i class="fa fa-exclamation-circle btn-position"></i>'.$lang['advice']['about'].'' ) );

	$xt = $twig->loadTemplate($tpath['about'].'about.tpl');
	$tVars = array();
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$about = 'версия 0.3';
	
	$tVars = array(
		'global' => $lang['advice']['about'],
		'header' => $about,
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

function main()
{global $twig, $lang, $breadcrumb;
	
	$tpath = locatePluginTemplates(array('main', 'general.from'), 'advice', 1);
	$breadcrumb = breadcrumb('<i class="fa fa-bullhorn btn-position"></i><span class="text-semibold">'.$lang['advice']['advice'].'</span>', array('?mod=extras' => '<i class="fa fa-puzzle-piece btn-position"></i>'.$lang['extras'].'', '?mod=extra-config&plugin=advice' => '<i class="fa fa-bullhorn btn-position"></i>'.$lang['advice']['advice'].'' ) );

	if (isset($_REQUEST['submit'])){
		pluginSetVariable('advice', 'localsource', (int)$_REQUEST['localsource']);
		pluginSetVariable('advice', 'ubbcodes', intval($_REQUEST['ubbcodes']));
		pluginSetVariable('advice', 'minlength', intval($_REQUEST['minlength']));
		pluginSetVariable('advice', 'maxlength', intval($_REQUEST['maxlength']));
		pluginSetVariable('advice', 'guests', intval($_REQUEST['guests']));
		pluginSetVariable('advice', 'ecaptcha', intval($_REQUEST['ecaptcha']));
		pluginSetVariable('advice', 'perpage', intval($_REQUEST['perpage']));
		pluginSetVariable('advice', 'order',  secure_html($_REQUEST['order']));

		pluginsSaveConfig();
		msg(array("type" => "info", "info" => "сохранение прошло успешно"));
		return print_msg( 'info', ''.$lang['advice']['advice'].'', 'Cохранение прошло успешно', 'javascript:history.go(-1)' );
	}

	$ubbcodes = pluginGetVariable('advice', 'ubbcodes');
	$ubbcodes = '<option value="0" '.($ubbcodes==0?'selected':'').'>'.$lang['noa'].'</option><option value="1" '.($ubbcodes==1?'selected':'').'>'.$lang['yesa'].'</option>';
	$minlength = pluginGetVariable('advice', 'minlength');
	$maxlength = pluginGetVariable('advice', 'maxlength');
	$guests = pluginGetVariable('advice', 'guests');
	$guests = '<option value="0" '.($guests==0?'selected':'').'>'.$lang['noa'].'</option><option value="1" '.($guests==1?'selected':'').'>'.$lang['yesa'].'</option>';
	$ecaptcha = pluginGetVariable('advice', 'ecaptcha');
	$ecaptcha = '<option value="0" '.($ecaptcha==0?'selected':'').'>'.$lang['noa'].'</option><option value="1" '.($ecaptcha==1?'selected':'').'>'.$lang['yesa'].'</option>';
	$perpage = pluginGetVariable('advice', 'perpage');
	$order = pluginGetVariable('advice', 'order');
	$order = '<option value="DESC" '.($order=='DESC'?'selected':'').'>По убыванию</option><option value="ASC" '.($order=='ASC'?'selected':'').'>По возрастанию</option>';
	
	$xt = $twig->loadTemplate($tpath['general.from'].'general.from.tpl');
	$xg = $twig->loadTemplate($tpath['main'].'main.tpl');
	
	$tVars = array(
		'localsource'   => MakeDropDown(array(0 => 'Шаблон сайта', 1 => 'Плагина'), 'localsource', (int)pluginGetVariable('advice', 'localsource')),
		'ubbcodes'   	=> $ubbcodes,
		'minlength'   	=> $minlength,
		'maxlength'   	=> $maxlength,
		'guests'   		=> $guests,
		'ecaptcha'   	=> $ecaptcha,
		'perpage'   	=> $perpage,
		'order'   		=> $order,
									
	);
	
	$tVars = array(
		'global' => 'Общие',
		'header' => '<i class="fa fa-exclamation-circle"></i> <a href="?mod=extra-config&plugin=advice&action=about">'.$lang['advice']['about'].'</a>',
		'entries' => $xt->render($tVars)
	);
	
	print $xg->render($tVars);
}

?>