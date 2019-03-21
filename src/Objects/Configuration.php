<?php

namespace Botnyx\Sfe\Shared\Objects;


/*
	new Botnyx\Sfe\Shared\Objects\ConfigurationException();
*/



class Configuration {

	var $type; /* frontend,backend,auth,cdn */

	var $paths;
	var $slim;
	var $twig;
	var $role; /* role specific settings */


	function __construct( $settingsArray ){

		/* check if var is array */
		if( !is_array($settingsArray) ){
			throw new ConfigurationException("Invalid configuration format.");
		}


		/* parse the settings array */
		try{
			$this->parse($settingsArray);
		}catch(ConfigurationException $e){
			//fatal!
			
			$html = \Botnyx\Sfe\Shared\Exception::configurationException($e);
			$end = \Botnyx\Sfe\Shared\Exception::kill($html);
			die();
		}catch(\Exception $e){
			$html = \Botnyx\Sfe\Shared\Exception::unknownException($e);
			$end = \Botnyx\Sfe\Shared\Exception::kill($html);
			die();
		}

	}




	private function frontendSettings($settings){
		/*  */
		return new \Botnyx\Sfe\Frontend\Core\Objects\config\Frontend($settings);
	}

	private function backendSettings($settings){
		/*  */
		return new \Botnyx\Sfe\Backend\Core\Objects\config\Backend($settings);
	}

	private function cdnSettings($settings){

	}

	private function authSettings($settings){
		if(!array_key_exists('clientId',$settings['sfeAuth'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `clientId` in the `sfeAuth` section.");
		}
		$this->clientId = $settings['sfeAuth']['clientId'];
		$section_settings = $settings['sfeBackend'];

		if(!array_key_exists('clientSecret',$settings['sfeAuth'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `clientSecret` in the `sfeAuth` section.");
		}
	}


	private function pathSettings($settings){
		/* Check if section exists */
		if(!array_key_exists('paths',$settings)){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing paths.");
		}

		$paths = new config\Paths();

		/* path.root */
		if(!array_key_exists('root',$settings['paths'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `root` path.");
		}
		if( !file_exists($settings['paths']['root']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Folder `root`  not found.");
		}
		$paths->root = $settings['paths']['root'];


		/* path.templates */
		if(!array_key_exists('templates',$settings['paths'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `templates` path.");

		}elseif( !file_exists($settings['paths']['templates']) ){
			throw new ConfigurationException($settings['paths']['templates']." Fatal Error in Configuration.ini : Folder `templates`  not found.");
		}
		$paths->templates = $settings['paths']['templates'];


		/* path.publichtml */
		if(!array_key_exists('publichtml',$settings['paths'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `publichtml` path.");

		}elseif( !file_exists($settings['paths']['publichtml']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Folder `publichtml`  not found.");
		}
		$paths->publichtml = $settings['paths']['publichtml'];


		/* path.logs */
		if(!array_key_exists('logs',$settings['paths'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `logs` path.");

		}elseif( !file_exists($settings['paths']['logs']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Folder `logs`  not found.");
		}
		$paths->logs = $settings['paths']['logs'];


		/* path.temp */
		if(!array_key_exists('temp',$settings['paths'])){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `temp` path.");

		}elseif( !file_exists($settings['paths']['temp']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Folder `temp`  not found.");
		}
		$paths->temp = $settings['paths']['temp'];

		return $paths;

	}

	private function slimSettings($settings){
		/* Slim Framework related section */
		if(!array_key_exists('slim',$settings)){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `slim` section.");
		}
		$slim = new config\Slim();

		/* slim.debug */
		if( !array_key_exists('debug',$settings['slim']) ){
				throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `debug` in `slim` section.");
		}
		$slim->debug=$settings['slim']['debug'];

		/* slim.loglevel */
		if( !array_key_exists('loglevel',$settings['slim']) ){
				throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `loglevel` in `slim` section.");
		}
		$slim->loglevel=$settings['slim']['loglevel'];


		/* slim.routercachefile */
		if( !array_key_exists('routercachefile',$settings['slim']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `routercachefile` in `slim` section.");
		}
		if($settings['slim']['routercachefile']==""){
			$slim->routercachefile=false;
		}else{
			$slim->routercachefile=$settings['slim']['routercachefile'];
		}

		return $slim;
	}

	private function twigSettings($settings){

		/* Twig template related section */
		if(!array_key_exists('twig',$settings)){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `twig` section.");
		}
		$twig = new config\Twig();

		/* twig.cache */
		if( !array_key_exists('cache',$settings['twig']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `cache` in `twig` section.");
		}
		$twig->cache = $settings['twig']['cache'];

		/* twig.debug */
		if( !array_key_exists('debug',$settings['twig']) ){
			throw new ConfigurationException("Fatal Error in Configuration.ini : Missing `debug` in `twig` section.");
		}
		$twig->debug = $settings['twig']['debug'];


		//die();

		/* twig.extensions */
		if( array_key_exists('extension',$settings['twig']) ){
			foreach($settings['twig']['extension']as $e){
				$twig->addExtension($e);
			}
		}else{
			$twig->extension = array();
		}


		return $twig;
	}

	private function parse($settings){

		/* parse and validate the settings */
		$this->paths = $this->pathSettings($settings);

		/* parse and validate the settings  */
		$this->slim = $this->slimSettings($settings);

		/* parse and validate the settings  */
		$this->twig =$this->twigSettings($settings);



		$this->type=false;

		/* Check if section exists */
		if(array_key_exists('sfeFrontend',$settings) && $this->type==false ){
			$this->type='frontend';
			$this->role =$this->frontendSettings($settings);
		}

		/* Check if section exists */
		if(array_key_exists('sfeBackend',$settings) ){
			if($this->type==false){
				$this->type='backend';
				$this->role = $this->backendSettings($settings);
			}else{
				throw new ConfigurationException("FAIL: Server-configuration cant have more than 1 role.");
			}
		}


		/* Check if section exists */
		if(array_key_exists('sfeAuth',$settings) && $this->type==false){
			if($this->type==false){
				$this->type='auth';
				$this->role =$this->authSettings($settings);
			}else{
				throw new ConfigurationException("FAIL: Server-configuration cant have more than 1.. role.");
			}
		}

		if(array_key_exists('sfeCdn',$settings)&& $this->type==false){
			if($this->type==false){
				$this->type='cdn';
				$this->role =$this->cdnSettings($settings);
			}else{
				throw new ConfigurationException("FAIL: Server-configuration cant have more than 1.. role.");
			}
		}


		if($this->type==false){
			throw new ConfigurationException("FAIL: Server has no role defined.");
		}



	}



















	private function Exception( $type,$value ) {
		throw new \Exception("FAIL: ".ucfirst($type)." wanted " . gettype($value) . " received");
	}

	private function x__set($name, $value) {
        switch ($name) {
            case "clientid":
                $valid = is_string($value);
				$error = array( 'String',$value );
                break;
            case "paths":
                $valid = is_array($value);
				$error = array( 'Array',$value );
                break;
            case "extension":
                $valid = is_object($value);
				$error = array( 'Object',$value );
                break;
            case "percent":
                $valid = is_float($value) && $value >= 0 && $value <= 100;
				$error = array( 'Float',$value );
                break;
            default:
                $valid = false; // allow all other attempts to set values (or make this false to deny them)
				$error = array( 'Unknown variable!' );
        }

        if ($valid) {
            $this->{$name} = $value;

            // just for demonstration
            #echo "pass: Set \$this->$name = ";
            #var_dump($value);
        } else {
            // throw an error, raise an exception, or otherwise respond
			if( count($error)==1 ){
				new \Exception("FAIL: "."FAIL: Cannot set \$this->$name = ");
			}else{
				$this->Exception( $type,$value );
			}

            // just for demonstration
            //echo "FAIL: Cannot set \$this->$name = ";
            #var_dump($value);

        }
    }
















}
