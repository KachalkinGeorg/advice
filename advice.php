<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

register_plugin_page('advice','com','advice_com');
register_plugin_page('advice','edit','advice_com_edit');

function advice_com($id, $altname) {
	global $tpl, $template, $TemplateCache, $ip, $twig, $mysql, $parse, $config, $userROW, $lang;

	$tpath = locatePluginTemplates(array('advice', 'advice_form', ':advice.css'), 'advice', pluginGetVariable('advice', 'localsource'), pluginGetVariable('advice', 'skins') ? pluginGetVariable('advice', 'skins') : 'default');
	register_stylesheet($tpath['url::advice.css'].'/advice.css');
	
	if(isset($_POST['add_advice'])) {
		if (!is_array($userROW)) {
			$_POST['author'] = secure_html(convert(trim($_POST['author'])));
			if(!strlen($_POST['author'])) $errors[] .= "Вы не ввели свое имя.";

			if (pluginGetVariable('advice','ecaptcha')) {
				$captcha = $_REQUEST['vcode'];
				if (!$captcha || ($_SESSION['captcha'] != $captcha)) {
					$errors[] .= "Проверочный код введен неправильно.";
				}
			}
			$_SESSION['captcha'] = rand(00000, 99999);
		}
		
		if(!strlen(trim($_POST['content']))) { $errors[] = "Не введен текст сообщения."; }
		
		//handle message
		$message = secure_html(trim($_POST['content']));
		$minl = pluginGetVariable('advice','minlength');
		$maxl = pluginGetVariable('advice','maxlength');
		if (strlen($message) < $minl || strlen($message) > $maxl) {
			$errors[] .= "Текст сообщения должен быть в пределах от $minl до $maxl символов.";
		}
		$message = str_replace("\r\n", "<br />", $message);
		
		if(is_array($userROW)) {
			$author = $userROW['name'];
		} else {
			$author = $_POST['author'];
		}
		
		if(!is_array($errors)) {
			$time = time() + ($config['date_adjust'] * 60);
			$mysql->query("INSERT INTO ".prefix."_advice_com (postdate, post, message, author, ip) values (".db_squote($time).", ".db_squote($id).", ".db_squote($message).", ".db_squote($author).", ".db_squote($ip).")");
		}
	}
	
	if($_REQUEST['mode']== 'del')
	{
		if(is_array($userROW) && ($userROW['status'] == "1"))
		{
			if (!is_array($mysql->record("SELECT id FROM ".prefix."_advice_com WHERE id=".db_squote(intval($_REQUEST['id'])))))
			{
				$template['vars']['mainblock'] = "Такой записи не существует";
				return;
			}
			$mysql->query("DELETE FROM ".prefix."_advice_com WHERE id = ".intval($_REQUEST['id']));
			
			$nrow = $mysql->record("select * from " . prefix . "_news where id = " . db_squote($id));
			header('Location: ' . newsGenerateLink($nrow));
		}
	}
	
	//display form
	$tfvars['vars']['author'] = ($userROW)?'Ваш отзыв будет опубликован от имени <strong>'.$userROW['name'].'</strong><input type="hidden" name="author" value="'.$userROW['name'].'"/><br/><br/>':'Имя: <br /><input type="text" name="author" /><br/><br/>';
	$tfvars['vars']['ip'] = $ip;
	
	$tfvars['vars']['bbcodes'] = (pluginGetVariable('advice','ubbcodes')) ? BBCodes('content') :"";
	
	$tfvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = '';
	if (pluginGetVariable('advice','ecaptcha'))
	{
		$tfvars['regx']["'\[captcha\](.*?)\[/captcha\]'si"] = (is_array($userROW))?'':'$1';
		if (!is_array($userROW))
		{
			$tfvars['vars']['admin_url'] = admin_url;
			//@session_register('captcha');
			$_SESSION['captcha'] = mt_rand(00000, 99999);
			$tfvars['vars']['captcha'] = '';
		}
	}
	
	
	if (!is_array($userROW) && !pluginGetVariable('advice','guests'))
	{
		$tfvars['regx']["'\[textarea\](.*?)\[/textarea\]'si"] = 'Гостям нельзя оставлять отзывы. Зарегистрируйтесь.';
	} else {
		$tfvars['regx']["'\[textarea\](.*?)\[/textarea\]'si"] = '\\1';
	}
	
	$tpl -> template('advice_form', $tpath['advice_form']);
	$tpl -> vars('advice_form', $tfvars);
	$tvars['vars']['forma'] = $tpl -> show('advice_form');
	
	//comments
	$perpage = intval(pluginGetVariable('advice', 'perpage'));
	
	if (($perpage < 2) or ($perpage > 2000)) { $perpage = 10; }
	
	$page = isset($params['page'])?intval($params['page']):intval($_REQUEST['page']);
	$page		= isset($page)?$page:0;
	if ($page < 1)	$page = 1;
	if (!$start)	$start = ($page - 1)* $perpage;
	
	$total_count = $mysql-> result("SELECT COUNT(*) AS num FROM ".prefix."_advice_com WHERE post = ".$id."");

	$PagesCount = ceil($total_count / $perpage);
	
	if ($PagesCount > 1 && $PagesCount >= $page){
		$paginationParams = checkLinkAvailable('advice', 'com') ?
			array('pluginName' => 'advice', 'pluginHandler' => 'com', 'params' => array('cat' => $id, 'altname' => $altname), 'xparams' => array(), 'paginator' => array('page', 0, false)) :
			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'advice', 'handler' => 'com'), 'xparams' => array('cat' => $id, 'altname' => $altname), 'paginator' => array('page', 1, false));

		templateLoadVariables(true); 
		$navigations = $TemplateCache['site']['#variables']['navigation'];
	}
	
	$order = pluginGetVariable('advice', 'order');
	if($total_count == 0) {
		$tvars['vars']['comments'] = "Отзывов пока нет!";
	} else {
		$tvars['vars']['comments'] = advice_records($id, $order, $start, $perpage);
	}
	
	$tvars['vars']['pages'] = generatePagination($page, 1, $PagesCount, 10, $paginationParams, $navigations);
	
	if(isset($errors)) {
		$tvars['regx']["'\[error\](.*?)\[/error\]'si"] = '\\1';
		foreach($errors as $error) {
			$tvars['vars']['errors'] = msg(array("type" => "error", "text" => $error), 0, 2);
		}
	} else {
		$tvars['regx']["'\[error\](.*?)\[/error\]'si"] = '';
	}
	
	$tpl -> template('advice', $tpath['advice']);
	$tpl -> vars('advice', $tvars);
	$output = $tpl -> show('advice');
	
	return $output;
}

function advice_records($id, $order, $start, $perpage) {
	global $mysql, $tpl, $userROW, $config, $parse;
	
	$tpath = locatePluginTemplates(array('advice_com'), 'advice', pluginGetVariable('advice', 'localsource'), pluginGetVariable('advice', 'skins') ? pluginGetVariable('advice', 'skins') : 'default');

	if(is_array($userROW) && ($userROW['status'] == "1")) {
		$tvars['regx']["'\[moderate\](.*?)\[/moderate\]'si"] = '\\1';
	} else {
		$tvars['regx']["'\[moderate\](.*?)\[/moderate\]'si"] = '';
	}
	foreach ($mysql->select("SELECT * FROM ".prefix."_advice_com WHERE post = {$id} ORDER BY id {$order} LIMIT {$start}, {$perpage}") as $row) {
		if (pluginGetVariable('advice','ubbcodes'))	{ $row['message'] = $parse -> smilies($row['message']); }
		
		$urow = $mysql->record("select * from " . uprefix . "_users where name = " . db_squote($row['author']));
		
		$userAvatar = userGetAvatar($urow);
		
		$comnum++;
		
		$tvars['vars'] = array (
			'date' 		=> date(pluginGetVariable('advice', 'date'), $row['postdate']),
			'message' 	=> $parse->bbcodes($row['message']),
			'author' 	=> $row['author'],
			'ip' 		=> $row['ip'],
			'avatar'    => $userAvatar[1],
			'comnum' 	=> $comnum
		);
		
		$editlink = generateLink('core', 'plugin', array('plugin' => 'advice', 'handler' => 'edit'), array('id' => $row['id']));
		if ($nrow = $mysql->record("select * from " . prefix . "_news where id = " . db_squote($id))) {
			$dellink = newsGenerateLink($nrow);
		} else {
			$dellink = $config['home_url'];
		}

		$tvars['vars']['edit'] = '<a href="'.$editlink.'" target="_blank"> Редактировать </a>';
		$tvars['vars']['del'] = '<a href="'.$dellink.'?mode=del&id='.$row['id'].'">Удалить</a>';
		
		$tpl -> template('advice_com', $tpath['advice_com']);
		$tpl -> vars('advice_com', $tvars);
		$comments .= $tpl -> show('advice_com');
	}
	return $comments;
}

function advice_com_edit() {
	global $template, $tpl, $userROW, $ip, $config, $mysql;
	 if(is_array($userROW) && $userROW['status'] == "1") {
	 	if(isSet($_REQUEST['go'])) {
	 		 $author = secure_html(trim($_REQUEST['author']));
			 $message = secure_html(trim($_REQUEST['content']));
		     $message = str_replace("\r\n", "<br />", $message);
		     
		     $mysql->query("UPDATE ".prefix."_advice_com SET author =".db_squote($author).", message=".db_squote($message)." WHERE id=".$_REQUEST['id']);
		     echo "<script>window.close();</script>";
		     
 		} else {
	 		if (!is_array($row = $mysql->record("SELECT * FROM ".prefix."_advice_com WHERE id=".db_squote(intval($_REQUEST['id']))))) {
				$template['vars']['mainblock'] = "Такой записи не существует";
				return;
			}
	     
		 $row['message'] = str_replace("<br />", "\r\n", $row['message']);
		 $template['vars']['mainblock'] = "<form method='post' action=''><input type='text' name='author' value='".$row['author']."'><br/><br/>";
		 $template['vars']['mainblock'] .= "<textarea name='content' id=\"content\" style='width: 95%;' rows='8'>".$row['message']."</textarea><br/><br/>";
		 $template['vars']['mainblock'] .= "<input type='hidden' name='id' value='".$row['id']."'><input type='submit' name='go' value='Отредактировать'></form>";
	 		
		}
		 
	 } else {
	 	$template['vars']['mainblock'] = "Что тебе здесь надо?!";
	 }
}

class NewsAdviceNewsfilter extends NewsFilter {
	
	function showNews($newsID, $SQLnews, &$tvars, $mode = array()) {
		global $mysql;
		
		$advice_all = $mysql-> result("SELECT COUNT(*) AS num FROM ".prefix."_advice_com WHERE post = ".$newsID."");
		$tvars['vars']['advice_all'] = $advice_all;
		$tvars['vars']['advice'] = advice_com($newsID, $SQLnews['alt_name']);
	}
}

register_filter('news','advice', new NewsAdviceNewsfilter);

?>