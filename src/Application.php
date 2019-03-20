<?php

namespace Botnyx\Sfe\Shared;

class Application {

	protected $_configuration;

	function __construct($_settings){


		try{
			$configuration = new \Botnyx\Sfe\Shared\Objects\Configuration($_settings);
			//$this->parseSettings($_settings);
		}catch(Objects\ConfigurationException $e){
			// Botnyx\Sfe\Shared\Objects
			//fatal!
			echo "<h1>ConfigurationException</h1>";
			die($e->getMessage());
		}catch(\Exception $e){
			//fatal!
			die($e->getMessage());
		}
		
		
		catch( \Exception $e){
			echo "<h1>Main application error!</h1>";
			echo $e->getMessage();
			die(" - ");
		}
		
		$this->_configuration = new \Botnyx\Sfe\Shared\AppSettings($configuration);
		
		
		
	}
	
	function __get($name) {
		
		if($name=='configuration'){
			return $this->configuration;
		}elseif($name=='role'){
			return $this->configuration->type;
		}elseif($name=='paths'){
			return $this->configuration->paths;
		}elseif($name=='clientid'){
			return $this->configuration->role->clientid;
		}elseif($name=='clientsecret'){
			return $this->configuration->role->clientsecret;
		}else{
			throw new \Exception("Cant access '".$name."' in Application.");
		}
		
		
		
	}


	public function init(){
		
		/* Dependencies */
		
		
		switch ($this->_configuration->type) {
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

		
		/* Start the slim application */
		$app = $this->startSlim();


		/* get the container */
		$container = $app->getContainer();
		
		
		/* Add default error handlers. */
		$container = $this->ErrorHandlers($container);
		
		/* Get the role-specific container runtime */
		$container = $applicationCore->getContainer($container);


		/* Get the role-specific Middleware runtime */
		$app = $applicationCore->getMiddleware($app,$container);


		/* Get the role-specific Routes runtime */
		$app = $applicationCore->getRoutes($app,$container);

		
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
			'sfe'=>$this->_configuration,
			'settings' => [
				'debug'=> $this->_configuration->debug,
				/*'paths'=> $this->_configuration->paths,
				'hosts'=> $this->_configuration->hosts,
				'clientid'=> $this->_configuration->clientid,*/
				'displayErrorDetails' => $this->_configuration->slim('debug'), // set to false in production
				'routerCacheFile' => $this->_configuration->slim('routercachefile'),
				// Monolog settings
				'logger' => $this->slimLogger($this->_configuration->clientid, $this->_configuration->slim('loglevel') ),
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

	

	private function slimLogger($logname,$loglevel='debug'){
		$logger = array();
		$logger['name'] = $logname;
		$logger['path'] = isset($_ENV['docker']) ? 'php://stdout' : $this->_configuration->paths->logs.'/app-'.$this->_configuration->clientid.'.log';
		$logger['level'] = $this->_configuration->slim('loglevel'); //$this->logLevel($loglevel);
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

	
	
	
	
	private function ErrorHandlers($container){
		
		
		$container['phpErrorHandler'] = function ($c) {
			return function ($request, $response, $error) use ($c) {
				return $response->withStatus(500)
					->withHeader('Content-Type', 'text/html')
					->write('phpErrorHandler');
			};
		};
		
		
		$container['errorHandler'] = function ($c) {
			return function ($request, $response, $e) use ($c) {
				$e->getMessage();
				$e->getCode();
				$e->getLine();
				$e->getFile();
				//$e->getType();
				//$e->toString();
				
				
				print_r((string)$e );
				die();
				return $response->withStatus(500)
					->withHeader('Content-Type', 'text/html')
					->write('errorHandler');
			};	
		};
		/*
		$container['xnotFoundHandler'] = function ($c) {
			return function ($request, $response) use ($c) {
				return $response->withStatus(500)
					->withHeader('Content-Type', 'text/html')
					->write('notFoundHandler');
			};	
		};
		
		$c['notAllowedHandler'] = function ($c) {
			return function ($request, $response, $error) use ($c) {
				return $response->withStatus(500)
					->withHeader('Content-Type', 'text/html')
					->write('notAllowedHandler');
			};	
		};*/
		
		
		
		return $container;
	}
	
	
	
	
	
	
	
	
	
	

	/* dev helper to show errors. */
	public function show_errors(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL ^ E_NOTICE);
	}

}
