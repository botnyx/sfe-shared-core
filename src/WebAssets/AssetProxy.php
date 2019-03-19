<?php


namespace Botnyx\Sfe\Shared\WebAssets;


//namespace Botnyx\Sfe\Frontend\Core\WebAssets;

use Interop\Container\ContainerInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\PredisCache;


use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\PrivateCacheStrategy;
use Kevinrob\GuzzleCache\Strategy\PublicCacheStrategy;
use Kevinrob\GuzzleCache\Storage\DoctrineCacheStorage;

use Kevinrob\GuzzleCache\KeyValueHttpHeader;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use	GuzzleHttp\Exception\TransferException;


class AssetProxy{
	
	function __construct(ContainerInterface $container){
		
		$this->settings=$container->get('settings')['sfe'];
		$this->paths=$container->get('settings')['paths'];
		
		
		//$this->frontEndConfig =  $container->get('frontEndConfig');

		$cacheDirectory = sys_get_temp_dir();
		$this->debug = true;
		
		$this->cacher = $container->get('cache');
		
		// Create default HandlerStack
		$this->_stack = \GuzzleHttp\HandlerStack::create();
		$this->_stack->push(
		  new CacheMiddleware(
			new PrivateCacheStrategy(
			  new DoctrineCacheStorage(
				new FilesystemCache( $cacheDirectory )
			  )
			)
		  ),
		  'cache'
		);
		// Initialize the client with the handler option
		$this->client = new \GuzzleHttp\Client([
			/*'handler' => $this->_stack,*/
			'http_errors'=>true
		]);
		
		
	}
	
	function responseWithHeaders($response,$guzzleresponse){
		
		$headers = $guzzleresponse->getHeaders();
		
		
		if(array_key_exists('Cache-Control',$headers) ){
			// https://developer.mozilla.org/en-US/docs/Web/HTTP/Caching
			// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Cache-Control
			
			//print_r($headers);
			
			foreach($headers['Cache-Control'] as $headerval){
				$response = $response->withHeader('Cache-Control',$headerval);
			}
			//Cache-Control: public
			//Cache-Control: max-age=31536000
				
		}
		
		if(array_key_exists('Pragma',$headers) ){
			// https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Pragma
			// Use Pragma only for backwards compatibility with HTTP/1.0 clients.
			foreach($headers['Pragma'] as $headerval){
				$response = $response->withHeader('Pragma',$headerval);
			}
			
		}
		
		if(array_key_exists('Last-Modified',$headers) ){
			//https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Last-Modified
			foreach($headers['Last-Modified']as $headerval){
				$response = $response->withHeader('Last-Modified',$headerval);
			}
		}
		
		if(array_key_exists('Expires',$headers) ){
			foreach($headers['Expires']as $headerval){
				$response = $response->withHeader('Expires',$headerval);
			}
		}
		
		if(array_key_exists('Content-Type',$headers) ){
			foreach($headers['Content-Type']as $headerval){
				$response = $response->withHeader('Content-Type',$headerval);
			}
		}
		
		
		return $response;
	}
	
	function e500($response){
		$view = new \Slim\Views\Twig($this->paths->root.'/vendor/botnyx/sfe-shared-core/templates/errorPages', [
			'cache' => false
		]);
		return $view->render($response, 'HTTP500.html', [
			'debug'=>$this->debug,
			'error' => array("code"=>500,"message"=>"Backend AssetsProxy reports unknown error.")
		])->withStatus(404);
		//return $response->withStatus(404)->withHeader('Content-Type', 'text/html')->write('CUSTOM Page not found');
	}
	
	
	function e404($response){
		$view = new \Slim\Views\Twig($this->paths->root.'/vendor/botnyx/sfe-shared-core/templates/errorPages', [
			'cache' => false
		]);
		return $view->render($response, 'HTTP404.html', [
			'debug'=>$this->debug,
			'error' => array("code"=>404,"message"=>"Backend AssetsProxy reports 'File not Found.'")
		])->withStatus(404);
		//return $response->withStatus(404)->withHeader('Content-Type', 'text/html')->write('CUSTOM Page not found');
	}
	
	
	function get($response,$uri){
				
		try {	
			$res = $this->client->request('GET',$uri);	
			
		}catch(\Exception $e){
			throw new \Exception($e->getMessage(),$e->getCode());
			
		}
		
		
		return $this->responseWithHeaders($response->write($res->getBody()->getContents()),$res);
		
	}
	
}

