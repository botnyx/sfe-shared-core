<?php

namespace Botnyx\Sfe\Shared\Objects\config;


/*





*/


class SfeHosts {


	function __set($name, $value) {

		#var_dump($name);
		#var_dump($value);
		#var_dump(strpos($value,'http'));

		if(strpos($value,'http')!==false){
			throw new \Exception("FAIL: hostname (fqdn) cannot contain protocol 'http' or 'https'");
		}
        switch (strtoupper($name)) {

            case "SFEFRONTEND":
                $valid = is_string($value) ;
				$error = array( 'String',$value );
                break;

            case "SFEBACKEND":
                $valid = is_string($value) ;
				$error = array( 'String',$value );
                break;

            case "SFECDN":
                $valid = is_string($value) ;
				$error = array( 'String',$value );
                break;

            case "SFEAUTH":
                $valid = is_string($value) ;
				$error = array( 'String',$value );
                break;


			default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' );
        }

        if ($valid) {
            $this->{str_replace("sfe","",strtolower($name))} = $value;

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

	public function getUri($what=false){
		if($what==false){
			return array(
				"frontend"=>"https://".$this->frontend ,
				"backend"=>"https://".$this->backend ,
				"cdn"=>"https://".$this->cdn ,
				"auth"=>"https://".$this->auth
			);
		}else{
			return "https://".$this->{$what};
		}
	}

}
