<?php

namespace Botnyx\Sfe\Shared\Objects\config;



use Monolog;
/*

*/




class Slim {



	private function Exception( $type,$value ) {
		throw new \Exception("FAIL: ".ucfirst($type)." wanted " . gettype($value) ." (".$value.") received");
	}

	private function file_exists($file){
		if(file_exists($file)){
			throw new \Exception("FAIL: file doesnt exist (".$file.")");
		}else{
			return true;
		}
	}

	private function isRouterCacheFile($file){
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



	function __set($name, $value) {
        switch ($name) {
            case "debug":
                $valid = is_bool($value) ;
				$error = array( 'Boolean',$value );
                break;
            case "loglevel":
                $valid = $this->logLevel($value);
				$error = array( 'String',$value );
                break;
            case "routercachefile":
                $valid = $this->isRouterCacheFile($file);
								$error = array( 'Bool',$value );
                break;

			default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' );
        }

        if ($valid) {
            $this->{strtolower($name)} = $value;

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
