<?php 

/* Load oAuth related stuff */
if(array_key_exists('sfeAuth',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/oauth_routes.php') ){
	error_log('/src/oauth_routes.php');
	require_once(_SETTINGS['paths']['root'] .'/src/oauth_routes.php');
}
/* Load backend related stuff */
if(array_key_exists('sfeBackend',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/backend_routes.php')){
	error_log('/src/backend_routes.php');
	require_once(_SETTINGS['paths']['root'] .'/src/backend_routes.php');
}

/* Load Cdn related stuff */
if(array_key_exists('sfeCdn',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/cdn_routes.php')){
	error_log('/src/cdn_routes.php');
	require_once(_SETTINGS['paths']['root'] .'/src/cdn_routes.php');
}



/* Load Cdn related stuff */
if(array_key_exists('sfeFrontend',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/frontend_routes.php')){
	error_log('/src/frontend_routes.php');
	require_once(_SETTINGS['paths']['root'] .'/src/frontend_routes.php');
}
