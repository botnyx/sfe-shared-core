<?php

namespace Botnyx\Sfe\Shared;

class Application {
	
	
	function __construct($_settings){
		$this->settings = $_settings;
	}
	
	/* Create the Slim application */
	public function start(){
		
		$this->app = new \Slim\App([
			'debug' => $this->settings['slim']['debug'],
			'settings' => [

				'displayErrorDetails' => $this->settings['slim']['debug'], // set to false in production
				// Monolog settings
				'logger' => [
					'name' => 'slim-app',
					'path' => isset($_ENV['docker']) ? 'php://stdout' : $this->settings['paths']['logs'].'/app.log',
					'level' => \Monolog\Logger::DEBUG,
				],
				'addContentLengthHeader'=>false,  
		/*		'addContentLengthHeader'=>false 
					ALWAYS disable this, else  the error 
					PHP Fatal error:  Uncaught TypeError: fread() expects parameter 2 to be integer, string given 
					Zend\\Diactoros\\Stream->read('173') 
				*/
			],
		]);
		return $this->app;		
	}
	
	

	/* dev helper to show errors. */
	public function show_errors(){
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL ^ E_NOTICE);
	}

}