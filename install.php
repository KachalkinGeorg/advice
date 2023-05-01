<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Configuration file for plugin
//

pluginsLoadConfig();
LoadPluginLang('advice', 'config', '', '', '#');

// Load library
include_once(root . "/plugins/advice/lib/common.php");

function plugin_advice_install($action) {
	global $lang;
	
	if ($action != 'autoapply')
		loadPluginLang('advice', 'config', '', '', ':');
$db_update = array(
	array(
		'table'		=>	'advice_com',
		'action'	=>	'create',
		'key'    	=>  'primary key(`id`), KEY `c_post` (`post`)',
		'fields'	=>	array(
			array('action' => 'create', 'name' => 'id', 'type' => 'int', 'params' => 'not null auto_increment'),
			array('action' => 'create', 'name' => 'postdate',  'type' => 'int', 'params' => "not null default '0'"),
			array('action' => 'create', 'name' => 'post', 'type' => 'int', 'params' => "default '0'"),
			array('action' => 'create', 'name' => 'message', 'type' => 'text', 'params' => 'not null'),
			array('action' => 'create', 'name' => 'author', 'type' => 'varchar(50)', 'params' => "not null default ''"),
			array('action' => 'create', 'name' => 'ip', 'type' => 'varchar(40)', 'params' => "not null default ''"),
		)
	),	
);

	switch ($action) {
		case 'confirm':generate_install_page('advice', $lang['advice']['install']);break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('advice', $db_update, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('advice');
				create_advice_urls();
			} else {
				return false;
			}
			
			// Now we need to set some default params
			$params = array(
				'localsource' 	=> '1',
				'ubbcodes' 		=> 1,
				'minlength' 	=> 3,
				'maxlength' 	=> 500,
				'guests' 		=> 0,
				'ecaptcha' 		=> 1,
				'perpage' 		=> '1',
				'order' 		=> 'ASC',
				'date' 			=> 'j.m.Y - H:i',
			);
			foreach ($params as $k => $v) {
				extra_set_param('advice', $k, $v);
			}
			extra_commit_changes();
			break;
	}
	return true;
}
?>