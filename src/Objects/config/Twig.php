<?php

namespace Botnyx\Sfe\Shared\Objects\config;



use Monolog;
use \Twig\Extension;
/*

*/




class Twig {
	
	var $debug 			= true;
	var $cache			= false;
	
	var $extensions		= array();
	
	
	private function Exception( $type,$value ) {
		throw new \Exception("FAIL: ".ucfirst($type)." wanted " . gettype($value) . " received");
	}
	
	private function file_exists($file){
		if(file_exists($file)){
			throw new \Exception("FAIL: file doesnt exist (".$file.")");
		}else{
			return true;
		}
	}
	
	private function isCache($file){
		if(is_bool($file) && $file==false ){
			return true;
		}else{
			return $this->file_exists($file);
		}
	}
	
	private function logLevel($loglevel){
		
		switch(strtoupper($loglevel)){
			case "DEBUG":
				return \Monolog\Logger::DEBUG;
			case "INFO":
				return \Monolog\Logger::INFO;
			case "NOTICE":
				return \Monolog\Logger::NOTICE;
			case "WARNING":
				return \Monolog\Logger::WARNING;
			case "ERROR":
				return \Monolog\Logger::ERROR;
			case "CRITICAL":
				return \Monolog\Logger::CRITICAL;
			case "ALERT":
				return \Monolog\Logger::ALERT;
			case "EMERGENCY":
				return \Monolog\Logger::EMERGENCY;
			default:
				throw new \Exception("FAIL: Unknown SLIM loglevel");
				
		}
	}
	
	private function isExtension($value){
		/*
			Text: Provides useful filters for text manipulation;
			I18n: Adds internationalization support via the gettext library;
			Intl: Adds a filter for localization of DateTime objects, numbers and currency;
			Array: Provides useful filters for array manipulation;
			Date: Adds a filter for rendering the difference between dates.
		*/
		$allowedExtensions=array(
			"TEXT"=>new Twig_Extensions_Extension_Text(),
			"I18N"=>new Twig_Extensions_Extension_I18n(),
			"INTL"=>new Twig_Extensions_Extension_Intl(),
			"ARRAY"=>new Twig_Extensions_Extension_Array(),
			"DATE"=>new Twig_Extensions_Extension_Date(),
			"DEBUG"=>new DebugExtension()
		);
		$currentExtensions = $this->extensions;
		if(!array_key_exists(strtoupper($value),$allowedExtensions)){
			throw new \Exception("Unknown twig extension (".$value.")");
		}
		$currentExtensions[]=$allowedExtensions[strtoupper($value)];
		$this->extensions=$currentExtensions;
		return true;
	}
	
	function __set($name, $value) {
        switch ($name) {
            case "debug":
                $valid = is_bool($value) ;
				$error = array( 'Boolean',$value ); 
                break;
            case "extension":
                $valid = $this->isExtension($value);
				$error = array( 'String',$value ); 
                break;
            case "cache":
                $valid = $this->isCache($file);
				$error = array( 'Bool',$value ); 
                break;

			default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' ); 
        }

        if ($valid) {
            $this->{$name} = $value;

            // just for demonstration
            //echo "pass: Set \$this->$name = ";
            //var_dump($value);
        } else {
            // throw an error, raise an exception, or otherwise respond
			if( count($error)==1 ){
				new \Exception("FAIL: "."FAIL: Cannot set \$this->$name = ");
			}else{
				$this->Exception( $type,$value );
			}
			
            // just for demonstration
            //echo "FAIL: Cannot set \$this->$name = ";
            //var_dump($value);
			
        }
    }
	
	
}