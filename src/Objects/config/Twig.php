<?php

namespace Botnyx\Sfe\Shared\Objects\config;



use Monolog;
use Twig\Extension;
/*

*/




class Twig {

	#use \Twig\Extension;

	private function Exception( $type,$value ) {
		throw new \Exception("FAIL:: ".ucfirst($type)." wanted " . gettype($value) . " received");
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


	private function allowedExtensions(){
		$allowedExtensions=array(
			"TEXT"=>new \Twig\Extension\Twig_Extensions_Extension_Text(),
			"I18N"=>new \Twig\Extension\Twig_Extensions_Extension_I18n(),
			"INTL"=>new \Twig\Extension\Twig_Extensions_Extension_Intl(),
			"ARRAY"=>new \Twig\Extension\Twig_Extensions_Extension_Array(),
			"DATE"=>new \Twig\Extension\Twig_Extensions_Extension_Date(),
			"DEBUG"=>new \Twig\Extension\DebugExtension()
		);
		return $allowedExtensions;
	}

	private function isExtension($value){
		/*
			Text: Provides useful filters for text manipulation;
			I18n: Adds internationalization support via the gettext library;
			Intl: Adds a filter for localization of DateTime objects, numbers and currency;
			Array: Provides useful filters for array manipulation;
			Date: Adds a filter for rendering the difference between dates.
		*/
		//$allowed = $this->allowedExtensions();


		#print_r(new \Twig\Extension\Twig_Extensions_Extension_Text());
		print_r(new Twig_Extensions_Extension_I18n());
		#print_r(new \Twig\Extension\DebugExtension());

		die();
		$currentExtensions = $this->extensions;
		foreach($value as $v){

			if(!array_key_exists( strtoupper($v),$allowed )){
				//throw new \Exception("Unknown twig extension (".strtoupper($v).")");
			}else{
				//var_dump($v);
				//$currentExtensions[]=$this->allowedExtensions()[strtoupper($v)];
			}
		}
		die();

		//echo "val:".$value;







		$this->extensions=$currentExtensions;

		return true;
	}

	private function isDebug($value){
		if( $value=="1" ){
			return true;
		}elseif( $value=="" ){
			return true;
		}
		return false;
	}
	private function debugValue($value){
		if( $value=="1" ){
			return true;
		}
		return false;
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

	function __set($name, $value) {
		//echo "SET `".$name."`=>`".$value."`<br>";
        switch ($name) {
            case "debug":
				$valid = $this->isDebug($value);

				$value = $this->debugValue($value);
				$error = array(  );
                break;
            case "extension":
				//var_dump($value);
				$valid = $this->isExtension($value);
				$value = $this->extensions;

				//$error = array( 'String',$value );
				//$this->allowedExtensions()[$value];
                break;
            case "cache":
                $valid = $this->isCache($file);

				if($value==""){$value=false;}

				$error = array(  );
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
			//echo "<br>";
        } else {
            // throw an error, raise an exception, or otherwise respond
			if( count($error)==1 ){
				new \Exception("FAIL: "."FAIL: Cannot set \$this->$name = ");
			}else{
				$this->Exception( $error[0],$error[1] );
			}

            // just for demonstration
            //echo "FAIL: Cannot set \$this->$name = ";
            //var_dump($value);

        }
		//die();
    }


}
