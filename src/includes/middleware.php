<?php

/* Load middleware related stuff */

if(array_key_exists('sfeAuth',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/oauth_middleware.php')){
	error_log('/src/oauth_middleware.php');
	require_once(_SETTINGS['paths']['root'] .'/src/oauth_middleware.php');
}
/* Load backend related stuff */
if(array_key_exists('sfeBackend',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/backend_middleware.php')){
	error_log('/src/backend_middleware.php');
	require_once(_SETTINGS['paths']['root'] .'/src/backend_middleware.php');
}
/* Load cdn related stuff */
if(array_key_exists('sfeCdn',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/cdn_middleware.php')){
	error_log('/src/cdn_middleware.php');
	require_once(_SETTINGS['paths']['root'] .'/src/cdn_middleware.php');
}
