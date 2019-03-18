<?php

namespace Botnyx\Sfe\Shared\Objects\config;


/*


	
	
	
*/


class SfeHosts {
	
	var $frontend= "";
	var $backend = "";
	var $cdn	 = "";
	var $auth	 = "";
	
	function __set($name, $value) {
		echo "<hr>";
		var_dump($value);
		echo "<hr>";
		if(strpos($value,'http')==0){
			new \Exception("FAIL: hostname (fqdn) cannot contain protocol 'http' or 'https'");
		}
        switch (strtoupper($name)) {
				
            case "FRONTEND":
                $valid = is_string($value) ;
				$error = array( 'String',$value ); 
                break;
				
            case "BACKEND":
                $valid = is_string($value) ;
				$error = array( 'String',$value ); 
                break;
				
            case "CDN":
                $valid = is_string($value) ;
				$error = array( 'String',$value ); 
                break;
				
            case "AUTH":
                $valid = is_string($value) ;
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