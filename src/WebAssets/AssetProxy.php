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
		
				  
		print_r( $res->getHeaders() );
		
		die();
		#print_r($res->getHeader("Content-Type"));  // [0] => text/javascript; charset=utf-8
		#print_r($res->getHeader("Last-Modified")); // [0] => Wed, 13 Feb 2019 16:41:46 GMT
		#print_r($res->getHeader("cache-control")); // [0] => public, max-age=31536000
		
		return array( "html"=>$res->getBody()->getContents(), 
		"Last-Modified"=>strtotime( $res->getHeader("Last-Modified")[0] ),
		"cache-control"=>$res->getHeader("cache-control")[0],
		"Content-Type"=>$res->getHeader("Content-Type")[0] 	 ) ;
		
		
		
		$res = $response->write( $returnedData['html'] )->withHeader('Content-Type',$returnedData['Content-Type']);
		//$resWithExpires = $this->cache->withExpires($res, time() + 3600);
		$responseWithCacheHeader = $this->cacher->withExpires($res, time() + 3600);
		$responseWithCacheHeader = $this->cacher->withLastModified($responseWithCacheHeader, $returnedData['Last-Modified'] );
		return $responseWithCacheHeader;
		
	}
	
	
	
	function e404($response){
		$view = new \Slim\Views\Twig(_SETTINGS['paths']['root'].'/vendor/botnyx/sfe-shared-core/templates/errorPages', [
			'cache' => false
		]);
		return $view->render($response, 'HTTP404.html', [
			'debug'=>$this->debug,
			'error' => array("code"=>404,"message"=>"Backend AssetsProxy reports 'File not Found.'")
		])->withStatus(404);
		//return $response->withStatus(404)->withHeader('Content-Type', 'text/html')->write('CUSTOM Page not found');
	}
	
	
	function get($uri){
		
		try {
			
			$res = $this->client->request('GET',$uri);	
			
		}catch(\Exception $e){
			throw new \Exception($e->getMessage(),$e->getCode());
			
		}
		
		return $this->responseWithHeaders($response,$res);
		
		
		
		try {
			
			$res = $this->client->request('GET',$uri);	
			
		}catch(\Exception $e){
			throw new \Exception($e->getMessage(),$e->getCode());
			
		}
		#print_r($res->getHeaders());
		//die();
		#print_r($res->getHeader("Content-Type"));  // [0] => text/javascript; charset=utf-8
		#print_r($res->getHeader("Last-Modified")); // [0] => Wed, 13 Feb 2019 16:41:46 GMT
		#print_r($res->getHeader("cache-control")); // [0] => public, max-age=31536000
		
		return array( "html"=>$res->getBody()->getContents(), 
		"Last-Modified"=>strtotime( $res->getHeader("Last-Modified")[0] ),
		"cache-control"=>$res->getHeader("cache-control")[0],
		"Content-Type"=>$res->getHeader("Content-Type")[0] 	 ) ;
		
		//print_r( $res->getBody()->getContents() );
		//die();
		
		//"https://backend.servenow.nl/_/assets/css/".$args['path'];
		//"https://backend.servenow.nl/_/assets/js/".$args['path'];
		//return "https://backend.servenow.nl/_/assets/fonts/".$args['path'];
		
	}
	
}

