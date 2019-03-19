<?php

/* Load middleware related stuff * /


$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-auth-core/src/includes/middleware.php";
if(array_key_exists('sfeAuth',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}

$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-frontend-core/src/includes/middleware.php";
/* Load backend related stuff * /
if(array_key_exists('sfeFrontend',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}



$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-backend-core/src/includes/middleware.php";
/* Load backend related stuff * /
if(array_key_exists('sfeBackend',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}


$_include = _SETTINGS['paths']['root']."/vendor/botnyx/sfe-cdn-core/src/includes/middleware.php";
/* Load cdn related stuff * /
if(array_key_exists('sfeCdn',_SETTINGS) && file_exists($_include)){
	require_once($_include);
}
*/