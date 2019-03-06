<?php


$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-shared-core/src/includes/dependencies.php";
/* Load backend related stuff */
if(array_key_exists('sfeBackend',_SETTINGS) &&  file_exists($_include)){
	//error_log('/src/backend_dependencies.php');
	require_once($_include);
}


/* Load dependency related stuff */
#if(array_key_exists('sfeAuth',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/oauth_dependencies.php')){
#	error_log('/src/oauth_dependencies.php');
#	require_once(_SETTINGS['paths']['root'] .'/src/oauth_dependencies.php');
#}

/* Load backend related stuff */
#if(array_key_exists('sfeCdn',_SETTINGS) &&  file_exists(_SETTINGS['paths']['root'] .'/src/cdn_dependencies.php')){
#	error_log('/src/cdn_dependencies.php');
#	require_once(_SETTINGS['paths']['root'] .'/src/cdn_dependencies.php');
#}
