<?php


namespace Botnyx\Sfe\Shared;

//use Botnyx\Sfe\Shared\Objects\Configuration

class AppSettings {
	
	protected $type;
	protected $paths;
	protected $hosts;
	protected $clientid;
	
	protected $debug;
	
	protected $configuration;
	
	
	function __construct( \Botnyx\Sfe\Shared\Objects\Configuration $configuration){
		$this->configuration = $configuration;
	}
	
	
	
	
	function __get($name) {
		
		if($name=='configuration'){
			return $this->configuration->role;
			
		}elseif($name=='type'){
			return $this->configuration->type;
			
		}elseif($name=='paths'){
			return $this->configuration->paths;
			
		}elseif($name=='hosts'){
			return $this->configuration->role->hosts;
			
		}if($name=='clientid'){
			return $this->configuration->role->clientid;
			
		}elseif($name=='clientsecret'){
			return $this->configuration->role->clientsecret;
			
		}elseif($name=='debug'){
			return $this->configuration->twig->debug;
			
		}elseif($name=='role'){
			return $this->configuration->role;
			
		}else{
			throw new \Exception("Cant access '".$name."' in Application.");
		}
	}
	
	public function slim($section){
		$slimArray = (array)$this->configuration->slim;
		
		return $slimArray[$section];
		
	}
	public function twig($section){
		$twigArray = (array)$this->configuration->twig;
		
		return $twigArray[$section];
		
	}
	
}