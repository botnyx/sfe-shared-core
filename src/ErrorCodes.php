<?php

namespace Botnyx\Sfe\Shared;

class ErrorCodes{
	
	function __construct(){
		
	}
	
	function get($code){
		$array = array();
		
		
		
		$array = array_merge($array,$this->configErrors());
		
		//print_r($array);
		
		return $array["E".$code];
	}
	/*
		
		Configuration Errors
			
			
		Connection Errors
		
		Runtime Errors
	
	
	
		Frontend
		Backend 
		Authsrv
	
	*/
	
	function configErrors(){
		return array(
			"E1001"=>"missing configuration.ini",
			"E1002"=>"missing `root` path.",
			"E1003"=>"Folder `root`  not found.",
			"E1004"=>"Missing `templates` path.",
			"E1005"=>"Folder `templates`  not found.",
			"E1006"=>"Missing `publichtml` path.",
			"E1007"=>"Folder `publichtml`  not found.",
			"E1008"=>"Missing `logs` path.",
			"E1009"=>"Folder `logs`  not found.",
			"E1010"=>"Missing `temp` path.",
			"E1010"=>"Folder `temp`  not found.",
			"E1011"=>"Missing `slim` section.",
			"E1012"=>"Missing `debug` in `slim` section.",
			"E1013"=>"Missing `loglevel` in `slim` section.",
			"E1014"=>"Missing `routercachefile` in `slim` section.",
			"E1015"=>"Missing `twig` section.",
			"E1016"=>"Missing `cache` in `twig` section.",
			"E1017"=>"Missing `debug` in `twig` section.",
			
			"E1100"=>"Missing `clientId` in the `sfeFrontend` section.",
			"E1101"=>"Missing `sfeCdn` in the `sfeFrontend` section.",
			"E1102"=>"Missing `sfeBackend` in the `sfeFrontend` section.",
			"E1103"=>"Missing `sfeAuth` in the `sfeFrontend` section.",
			"E1104"=>"Unexpected `conn` in the `sfeFrontend` section.",
			"E1105"=>"Clientid not found in database",
			"E1106"=>"Clientid returned no frontend endpoints.",
			
			"E1200"=>"Missing `clientId` in the `sfeBackend` section.",
			"E1201"=>"Missing `clientSecret` in the `sfeBackend` section.",
			"E1202"=>"Missing `sfeAuth` in the `sfeBackend` section.",
			"E1203"=>"Missing `sfeCdn` in the `sfeBackend` section.",
			
			"E1300"=>"Missing `clientId` in the `sfeAuth` section.",
			"E1301"=>"Missing `clientSecret` in the `sfeAuth` section."
			
		);
	}
	
	
	function generic (){
		
	}
	
	
}