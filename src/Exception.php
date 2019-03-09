<?php

namespace Botnyx\Sfe\Shared;


class Exception {
	
	
	function __construct($message,$code){
		$errorCodes = new ErrorCodes();
		error_log( $code." - ".$message);
		throw new \Exception( $errorCodes->get((string)$code),$code );
	}
		
}