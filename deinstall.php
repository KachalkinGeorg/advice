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

$db_update = array(
	array(
		'table'		=>	'advice_com',
		'action'	=>	'drop',
	),
);

if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install($plugin, $db_update, 'deinstall')) {
		remove_advice_urls();
		plugin_mark_deinstalled($plugin);
	}
} else {
	generate_install_page($plugin, $lang['advice']['deinstall'], 'deinstall');
}

?>