<?php

//
function create_advice_urls()
{

    $ULIB = new urlLibrary();
    $ULIB->loadConfig();
    $ULIB->registerCommand('advice', 'com',
    	array ('vars' =>
        array( 	'cat' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Отзывы')),
				'altname' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Отзывы')),
        		'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация'))
        ),
        'descr'	=> array ('russian' => 'Отображать комментарии отзывов'),
    	)
    );
    $ULIB->saveConfig();
    $UHANDLER = new urlHandler();
    $UHANDLER->loadConfig();
 
    $UHANDLER->registerHandler(0,
    	array (
    	'pluginName' => 'advice',
    	'handlerName' => 'com',
    	'flagPrimary' => true,
    	'flagFailContinue' => false,
    	'flagDisabled' => false,
    	'rstyle' => 
    	array (
    	  'rcmd' => '/{cat}/{altname}/?page={page}',
    	  'regex' => '#^/advice/(\\d+)/(?:page/(\\d{1,4})/){0,1}$#',
    	  'regexMap' => 
    	  array (
    		1 => 'cat',
    		2 => 'page',
    	  ),
    	  'reqCheck' => 
    	  array (
    	  ),
    	  'setVars' => 
    	  array (
    	  ),
    	  'genrMAP' => 
    	  array (
    		0 => 
    		array (
    		  0 => 0,
    		  1 => '',
    		  2 => 0,
    		),
    		1 => 
    		array (
    		  0 => 0,
    		  1 => '',
    		  2 => 1,
    		),
    		2 => 
    		array (
    		  0 => 1,
    		  1 => 'altname',
    		  2 => 1,
    		),
    		3 => 
    		array (
    		  0 => 0,
    		  1 => '.html',
    		  2 => 1,
    		),
    		4 => 
    		array (
    		  0 => 0,
    		  1 => '?page=',
    		  2 => 1,
    		),
    		5 => 
    		array (
    		  0 => 1,
    		  1 => 'page',
    		  2 => 1,
    		),
    		6 => 
    		array (
    		  0 => 0,
    		  1 => '',
    		  2 => 1,
    		),
    	  ),
    	),
      )
    );
    
    $UHANDLER->saveConfig();
}

function remove_advice_urls()
{
    $ULIB = new urlLibrary();
    $ULIB->loadConfig();
    $ULIB->removeCommand('advice', 'com');
    $ULIB->saveConfig();
    $UHANDLER = new urlHandler();
    $UHANDLER->loadConfig();
    $UHANDLER->removePluginHandlers('advice', 'com');
    $UHANDLER->saveConfig();
}
