<?php

/* Load oAuth related stuff */
#if(array_key_exists('sfeAuth',_SETTINGS) && file_exists(_SETTINGS['paths']['root'] .'/src/oauth_routes.php') ){
#	error_log('/src/oauth_routes.php');
#	require_once(_SETTINGS['paths']['root'] .'/src/oauth_routes.php');
#}


$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-backend-core/src/includes/routes.php";
/* Load backend related stuff */
if(array_key_exists('sfeBackend',_SETTINGS) && file_exists( $_include )){
	require_once($_include);
}

$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-cdn-core/src/includes/routes.php";
/* Load Cdn related stuff */
if(array_key_exists('sfeCdn',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}

$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-auth-core/src/includes/routes.php";
/* Load Cdn related stuff */
if(array_key_exists('sfeAuth',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}



$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-frontend-core/src/includes/routes.php";
/* Load Cdn related stuff */
if(array_key_exists('sfeFrontend',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}










