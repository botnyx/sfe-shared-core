<?php

namespace Botnyx\Sfe\Shared\Objects\config;


/*

*/




class Paths {



	private function Exception( $type,$value ) {
		throw new \Exception("FAIL: ".ucfirst($type)." wanted " . gettype($value) . " received");
	}

	private function file_exists($file){
		if(!file_exists($file)){
			throw new \Exception("FAIL: file doesnt exist (".$file.")");
		}else{
			return true;
		}
	}


	function __set($name, $value) {
        switch ($name) {
            case "root":
                $valid = is_string($value) && $this->file_exists($value);
				$error = array( 'String',$value );
                break;
            case "templates":
                $valid = is_string($value) && $this->file_exists($value);
				$error = array( 'String',$value );
                break;
            case "publichtml":
                $valid = is_string($value) && $this->file_exists($value);
				$error = array( 'String',$value );
                break;
            case "logs":
                $valid = is_string($value) && $this->file_exists($value);
				$error = array( 'String',$value );
                break;
            case "temp":
                $valid = is_string($value) && $this->file_exists($value);
				$error = array( 'String',$value );
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
