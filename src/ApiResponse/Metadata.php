<?php


namespace Botnyx\Sfe\Shared\ApiResponse;

/*

{
	"code":200,
	"status":"ok",
	"statusmsg": "",
	"metadata" : {  
		"page_number": 5,
		"page_size": 20,
		"total_record_count": 521
	},
	"data": [
		{
		  "id": 1,
		  "name": "Widget #1"
		},
		{
		  "id": 2,
		  "name": "Widget #2"
		},
		{
		  "id": 3,
		  "name": "Widget #3"
		}
	]
}

*/
class Metadata{
	
	var $metadata = array();
	
	function __construct($options){
		
		if(array_key_exists('pagination',$options) ){
			$this->pagination( $options['pagination']['page_number'], $options['pagination']['page_size'], $options['pagination']['total_record_count']);
		}
		
	}
	
	function get(){
		return $this->metadata;
	}
	
	function pagination( $page, $size,$total){
		$res=array(); 
		$res["page_number"]	=$page;
		$res["page_size"]	=$size;
		$res["total_record_count"]=$total;
		$o = $this->metadata;
		$this->metadata =  array_merge($o,$res);
		//return $res;
	}
}