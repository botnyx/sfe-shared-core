<?php


namespace Botnyx\Sfe\Shared;

use Psr\Http\Message\ResponseInterface;

class ExceptionResponse{
	
	static function get(ResponseInterface $response,$errorcode,$string=false,$debug=false){
		
		if($debug==false){
			$string=false;
		}
		try{
			new \Botnyx\Sfe\Shared\Exception( $string, $errorcode );
			
		}catch(\Exception $e){
			$outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
			//$outputFormat->response($e->getMessage(),$e->getCode());
			return $response->withJson($outputFormat->response( $e->getMessage() ,$e->getCode() ,$string ) )->withStatus(500);
			
		}
		//return $this->response->withJson($outputFormat->response( $e->getMessage(),$e->getCode() ) )->withStatus(500);
	}
	
}