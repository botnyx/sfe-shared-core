<?php

namespace Botnyx\Sfe\Shared;

class ErrorCodes{
	/*
		Sfe error codes.
		
		$response,$errorcode,$string=false,$debug=false
		Slim reponse usage:
		return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,$code=2201,$additionalDebugtxt=string,$debug=bool);
		
		no debug msg:
		return \Botnyx\Sfe\Shared\ExceptionResponse::get($response,$code=2201);
		
		
		raise Exception (only the code):
		new Botnyx\Sfe\Shared\Exception($msg,$code)
	
	
	*/
	function __construct(){
		
	}
	
	function get($code){
		
		$array = array();
		/* Merge the arrays into 1 big one. */
		$array = array_merge($array,$this->configErrors());
		$array = array_merge($array,$this->filesystemErrors());
		$array = array_merge($array,$this->twigErrors());
		/* return the error */
		return $array["E".$code];
	}
	/*
		Configuration Errors
		Connection Errors
		Runtime Errors
	
	*/
	
	/*
		Application 1-99
		Frontend  100-199
		Backend   200-299
		Authsrv   300-399
	
	*/
	function twigErrors(){
		/*
		Application 1-99
		Frontend  100-199
		Backend   200-299
		Authsrv   300-399
		
			filesystem errors between 2000-2999
		*/
		return array(
			"E3200"=>"Twig_Error_Loader",
			"E3201"=>"Twig_Error_Syntax",
			"E3202"=>"Twig_Error_Runtime",
			"E3203"=>"Twig_Error_Security",
			"E3204"=>"Twig_Error_Generic",
			"E3205"=>"Twig Uncaught Exception",
			"E3206"=>"Css/JS Assets Not found Exception",
			"E3207"=>"Failure compiling sfe-backend-js",
			
		);
	}
	
	
	function configErrors(){
		/*
		Application 1-99
		Frontend  100-199
		Backend   200-299
		Authsrv   300-399
		
			config errors between 1000-1999
		*/
		
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
	
	
	
	
	function filesystemErrors(){
		/*
		Application 1-99
		Frontend  100-199
		Backend   200-299
		Authsrv   300-399
		
			filesystem errors between 2000-2999
		*/
		return array(
			"E2200"=>"folder _Clients/  not found in templates.",
			"E2201"=>"client template not found _Clients/[client_id]/",
			"E2202"=>"Origin template not found for client",
			"E2203"=>"requested path is non-existent."
			
		
		);
	}
	
	
}