<?php

namespace Botnyx\Sfe\Shared;


class Exception {
	
	
	function __construct($exception){
		
		$errorCodes = new ErrorCodes();
		error_log( $code." - ".$message);
		throw new \Exception( $errorCodes->get((string)$code),$code );
	}
	
}