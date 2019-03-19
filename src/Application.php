<?php

namespace Botnyx\Sfe\Shared;

class Application {

	private $configuration;


	function __construct($_settings){


		try{
			$this->configuration = new \Botnyx\Sfe\Shared\Objects\Configuration($_settings);
			//$this->parseSettings($_settings);
		}catch( \Exception $e){
			echo "<h1>Main application error!</h1>";
			echo $e->getMessage();
			die(" - ");
		}

		#echo "<pre>";

		#var_dump(new \Twig_Extensions_Extension_I18n());

		#print_r(new \Twig\Extension\DebugExtension());
		#echo "<pre>";

		#print_r($this->configuration);

		#die("xx");
	}
	
	function __get($name) {
		
		if($name=='configuration'){
			return $this->configuration;
		}else{
			throw new \Exception("Cant access '".$name."' in Application.");
		}
		
		
		
	}


	public function init(){




		/* Dependencies */
		switch ($this->configuration->type) {
			case "frontend":
				$applicationCore = new \Botnyx\Sfe\Frontend\Core\SlimLogic();
				break;
			case "backend":
				$applicationCore = new \Botnyx\Sfe\Backend\Core\SlimLogic();
				break;
			case "auth":
				$applicationCore = new \Botnyx\Sfe\Auth\Core\SlimLogic();
				break;
			case "cdn":
				$applicationCore = new \Botnyx\Sfe\Cdn\Core\SlimLogic();
				break;
			default:
				throw new \Exception("invalid role.");
				break;
		}

		#print_r($this->configuration->type);
		#die();

		/* Start the slim application */
		$app = $this->startSlim();


		/* get the container */
		$container = $app->getContainer();

		$container = $applicationCore->getContainer($container);



		/* Middleware */
		$app = $applicationCore->getMiddleware($app,$container);


		/* Routes */
		$app = $applicationCore->getRoutes($app,$container);


		#print_r($this);
		#die("x");

		return $app;
	}



	private function OBSOLETEparseSettings($settings){

		//$this->config = new Botnyx\Sfe\Shared\Objects\Configuration($settings);


		return;
		#print_r($settings);
		#die();

		/* Check if section exists */
		if(!array_key_exists('paths',$settings)){
			throw new \Exception("Fatal Error in Configuration.ini : Missing paths.");
		}
		if(!array_key_exists('root',$settings['paths'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `root` path.");

		}
		if( !file_exists($settings['paths']['root']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Folder `root`  not found.");
		}

		if(!array_key_exists('templates',$settings['paths'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `templates` path.");

		}elseif( !file_exists($settings['paths']['templates']) ){
			throw new \Exception($settings['paths']['templates']." Fatal Error in Configuration.ini : Folder `templates`  not found.");
		}

		if(!array_key_exists('publichtml',$settings['paths'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `publichtml` path.");

		}elseif( !file_exists($settings['paths']['publichtml']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Folder `publichtml`  not found.");
		}

		if(!array_key_exists('logs',$settings['paths'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `logs` path.");

		}elseif( !file_exists($settings['paths']['logs']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Folder `logs`  not found.");
		}

		if(!array_key_exists('temp',$settings['paths'])){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `temp` path.");

		}elseif( !file_exists($settings['paths']['temp']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Folder `temp`  not found.");
		}


		/* Slim Framework related section */
		if(!array_key_exists('slim',$settings)){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `slim` section.");
		}
		if( !array_key_exists('debug',$settings['slim']) ){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `debug` in `slim` section.");
		}
		if( !array_key_exists('loglevel',$settings['slim']) ){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `loglevel` in `slim` section.");
		}

		if( !array_key_exists('routercachefile',$settings['slim']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `routercachefile` in `slim` section.");
		}

		/* Twig template related section */
		if(!array_key_exists('twig',$settings)){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `twig` section.");
		}
		if( !array_key_exists('cache',$settings['twig']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `cache` in `twig` section.");
		}
		if( !array_key_exists('debug',$settings['twig']) ){
			throw new \Exception("Fatal Error in Configuration.ini : Missing `debug` in `twig` section.");
		}





		/* Check if section exists */
		if(array_key_exists('sfeFrontend',$settings)){
			if(!array_key_exists('clientId',$settings['sfeFrontend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `clientId` in the `sfeFrontend` section.");
			}
			$this->clientId = $settings['sfeFrontend']['clientId'];
			$section_settings = $settings['sfeFrontend'];

			if(!array_key_exists('sfeCdn',$settings['sfeFrontend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeCdn` in the `sfeFrontend` section.");
			}
			if(!array_key_exists('sfeBackend',$settings['sfeFrontend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeBackend` in the `sfeFrontend` section.");
			}
			if(!array_key_exists('sfeAuth',$settings['sfeFrontend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeAuth` in the `sfeFrontend` section.");
			}


			if(array_key_exists('conn',$settings['sfeFrontend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Unexpected `conn` in the `sfeFrontend` section.");
			}




		}

		/* Check if section exists */
		if(array_key_exists('sfeBackend',$settings)){
			if(!array_key_exists('clientId',$settings['sfeBackend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `clientId` in the `sfeBackend` section.");
			}
			$this->clientId = $settings['sfeBackend']['clientId'];
			$section_settings = $settings['sfeBackend'];

			if(!array_key_exists('clientSecret',$settings['sfeBackend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `clientSecret` in the `sfeBackend` section.");
			}else{
				$settings['sfeBackend']['clientSecret'] = new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['clientSecret']);
			}
			if(!array_key_exists('sfeAuth',$settings['sfeBackend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeAuth` in the `sfeBackend` section.");
			}
			if(!array_key_exists('sfeCdn',$settings['sfeBackend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `sfeCdn` in the `sfeBackend` section.");
			}

			if(!array_key_exists('conn',$settings['sfeBackend'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `conn` in the `sfeFrontend` section.");
			}else{
				$settings['sfeBackend']['conn']['dsn'] = new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['conn']['dsn']);
				$settings['sfeBackend']['conn']['dbuser'] = new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['conn']['dbuser']);
				$settings['sfeBackend']['conn']['dbpassword'] = new \Botnyx\Sfe\Shared\ProtectedValue($settings['sfeBackend']['conn']['dbpassword']);

			}


			//Botnyx\Sfe\Shared\ProtectedValue();
		}

		/* Check if section exists */
		if(array_key_exists('sfeAuth',$settings)){
			if(!array_key_exists('clientId',$settings['sfeAuth'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `clientId` in the `sfeAuth` section.");
			}
			$this->clientId = $settings['sfeAuth']['clientId'];
			$section_settings = $settings['sfeBackend'];

			if(!array_key_exists('clientSecret',$settings['sfeAuth'])){
				throw new \Exception("Fatal Error in Configuration.ini : Missing `clientSecret` in the `sfeAuth` section.");
			}
		}

		if(array_key_exists('sfeCdn',$settings)){

		}

		if( $settings['slim']['routercachefile']=="1" ){
			$settings['slim']['routercachefile']= $settings['paths']['temp']."/".$this->clientId."/RouterCache.php";
		}else{
			$settings['slim']['routercachefile']=false;
		}

		//var_dump($settings);

		$this->paths = $settings['paths'];
		$this->slim  = $settings['slim'];
		$section_settings['debug'] = $settings['slim']['debug'];
		$this->twig  = $settings['twig'];

		$this->settings = $section_settings;
	}


	/* Create the Slim application */
	public function startSlim(){
		$app = new \Slim\App([
			'settings' => [
				'paths'=> (array) $this->configuration->paths,
				'sfe'=> $this->configuration->role,
				'displayErrorDetails' => $this->configuration->slim->debug, // set to false in production
				'routerCacheFile' => $this->configuration->slim->routercachefile,
				// Monolog settings
				'logger' => $this->slimLogger($this->configuration->role->clientid, $this->configuration->slim->loglevel ),
				'addContentLengthHeader'=>false,
		/*		'addContentLengthHeader'=>false
					ALWAYS disable this, else  the error
					PHP Fatal error:  Uncaught TypeError: fread() expects parameter 2 to be integer, string given
					Zend\\Diactoros\\Stream->read('173')
				*/
			],
		]);
		return $app;
	}

	private function logLevel($loglevel){
		$levels= array(
			"DEBUG"		=>\Monolog\Logger::DEBUG,
			"INFO"		=>\Monolog\Logger::INFO,
			"NOTICE"	=>\Monolog\Logger::NOTICE,
			"WARNING"	=>\Monolog\Logger::WARNING,
			"ERROR"		=>\Monolog\Logger::ERROR,
			"CRITICAL"	=>\Monolog\Logger::CRITICAL,
			"ALERT"		=>\Monolog\Logger::ALERT,
			"EMERGENCY"	=>\Monolog\Logger::EMERGENCY
		);
		return $levels[strtoupper($loglevel)];
	}

	private function slimLogger($logname,$loglevel='debug'){
		$logger = array();
		$logger['name'] = $logname;
		$logger['path'] = isset($_ENV['docker']) ? 'php://stdout' : $this->configuration->paths->logs.'/app-'.$this->configuration->role->clientid.'.log';
		$logger['level'] = $this->logLevel($loglevel);
		return $logger;
	}







	private function slimDefaultSettings(){
		$settings = array();
		$setting['httpVersion'] = "1.1";
		$setting['responseChunkSize'] = 4096;
		$setting['outputBuffering'] = "append";
		$setting['determineRouteBeforeAppMiddleware'] = false;
		$setting['displayErrorDetails'] = false;
		$setting['addContentLengthHeader'] = false;
		$setting['routerCacheFile'] = false;
		return $setting;
	}


	/* dev helper to show errors. */
	public function show_errors(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL ^ E_NOTICE);
	}

}
