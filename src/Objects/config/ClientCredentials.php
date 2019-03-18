<?php

namespace Botnyx\Sfe\Shared\Objects\config;


/*

*/




class ClientCredentials {
	

	private function isClientidorSecret($value){
		if(!is_string($value)){
			throw new \Exception("FAIL: Cannot set clientId variable. ".gettype($value));
		}
		if(strlen($value)<5){
			throw new \Exception("FAIL: Cannot set clientId variable. (too short) ");
		}
		return true;
	}

	function __set($name, $value) {
        switch ($name) {

            case "clientid":
                $valid = $this->isClientidorSecret($value) ;
				$error = array( 'String',$value );
                break;

            case "clientsecret":
                $valid = $this->isClientidorSecret($value);
				$error = array( 'String',$value );
                break;


			default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' );
        }

        if ($valid) {
            $this->{$name} = new \Botnyx\Sfe\Shared\ProtectedValue($value);

            // just for demonstration
            //echo "pass: Set \$this->$name = ";
            //var_dump($value);
        } else {
            // throw an error, raise an exception, or otherwise respond
			if( count($error)==1 ){
				new \Exception("FAIL: Cannot set \$this->$name = ".gettype($value));
			}else{
				$this->Exception( $type,$value );
			}

            // just for demonstration
            //echo "FAIL: Cannot set \$this->$name = ";
            //var_dump($value);

        }
    }


}
