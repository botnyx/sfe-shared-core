<?php


namespace Botnyx\Sfe\Shared;

use Psr\Http\Message\ResponseInterface;

class ExceptionResponse{
	
	static function get(ResponseInterface $response,$errorcode){
		try{
			new \Botnyx\Sfe\Shared\Exception("ExceptionResponse",$errorcode );
		}catch(\Exception $e){
			//print_r($e);
			$outputFormat = new \Botnyx\Sfe\Shared\ApiResponse\Formatter();
			//$outputFormat->response($e->getMessage(),$e->getCode());
			return $response->withJson($outputFormat->response( $e->getMessage() ,$e->getCode() ) )->withStatus(500);
			
		}
		//return $this->response->withJson($outputFormat->response( $e->getMessage(),$e->getCode() ) )->withStatus(500);
	}
	
}