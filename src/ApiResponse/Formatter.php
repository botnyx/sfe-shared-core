<?php 

namespace Botnyx\Sfe\Shared\ApiResponse;


class Formatter {
	
	private function statusCodes(){
		$c[200]=array("status"=>"ok","statusmsg"=>"ok");
		
		$c[400]=array("status"=>"err","statusmsg"=>"Bad Request");
		$c[401]=array("status"=>"err","statusmsg"=>"Unauthorized");
		$c[402]=array("status"=>"err","statusmsg"=>"Payment Required");
		$c[403]=array("status"=>"err","statusmsg"=>"Forbidden");
		$c[404]=array("status"=>"err","statusmsg"=>"Not Found");
		
		
		$c[500]=array("status"=>"err","statusmsg"=>"server error");
		return $c;
	}
	
	
	function response($data,$option1=false,$option2=false){
		$statusCode = 200;
		if( is_int($option1) ){
			$statusCode = $option1;
			$options = $option2;
			
		}elseif( !is_int($option1) && !is_bool($option1) ){
			//$statusCode = $option1;
			$options = $option1;
			
		}elseif(  $option1==false && $option2==false ) {
			$statusCode = 200;
			$options = false;
			
		}else{
			throw new Exception("responseformat error");
		}
		
		/* the response array */
		$res = array();
		/*
			check the options, to add additional metadata 
		*/
		if($options!=false){
			$metadata = new metadata($options);	
			$res['metadata']=$metadata->get();
		}
		
		
		
		
		
		
		$res["code"]=$statusCode;
		$res["status"]=$this->statusCodes()[$statusCode]['status'];
		$res["statusmsg"]=$this->statusCodes()[$statusCode]['statusmsg'];
		
		$res["data"] = $data;
		
		return $res;
	}
	
}

