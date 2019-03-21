<?php

namespace Botnyx\Sfe\Shared;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class Exception {
	
	private $view;
	private $container;
	
	function __construct( Objects\Configuration $configuration){
		/*
			This happens before slim object is created.
		*/
		$this->paths = $configuration->paths;
		$this->sfe = $configuration->role;
		
		$this->debug=true;
			
		/*
			Create twig for the errors.
		*/
		$this->view = new \Slim\Views\Twig( $this->paths->root.'/vendor/botnyx/sfe-shared-core/templates/errorPages', [
			'cache' => $this->paths->temp."/errorpages"
		]);
		
		#$errorCodes = new ErrorCodes();
		#error_log( $code." - ".$message);
		#throw new \Exception( $errorCodes->get((string)$code),$code );
	}
	
	function setContainer( ContainerInterface $container){
		/*  */
		$this->container=$container;
	}
	
	
	
	
	function Exception($e){
		
	}
	function ExceptionToArray($error){
		return array( 
			"code"=>$error->getCode(),
			"message"=>$error->getMessage(),
			"file"=>$error->getFile(),
			"line"=>$error->getLine(),
			"trace"=>$error->getTrace()
		);
		//error.code }} , {{ error.message }} {{ error.file }}:{{ error.line 
	}
	public function notAllowedHandler($request, $response, $methods){
		return $response->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write('Method must be one of: ' . implode(', ', $methods));
		return $response->withStatus(500)->withHeader('Content-Type', 'text/html')->write('notAllowedHandler');
	}
	
	public function notFoundHandler($request, $response){
		/* 
			Content negotiation.  
		*/
		
		$responseType = 'json';
		if ($request->hasHeader('accept')) {
			if(strpos($request->getHeaderLine('accept'),'text/html')!==false ){
				$responseType = 'html';
				return $this->view->render($response,'HTTP404.html',[])->withStatus(404);
			}
		}
		
		
		$res["code"]		= $e->getCode;
		$res["status"]		= "Not found.";
		$res["statusmsg"]	= $e->getMessage();
		$res["data"] = false;
		
		return $response->withStatus(404)->withJson($res);
	}
	
	
	
	
	public function errorHandler($request, $response, $error){
		
		/*
			Get requested url info
		*/
		$uri = $request->getUri();
		$reqinfo = array(
			'host'=>$uri->getHost(),
			'path'=>$uri->getPath(),
			'query'=>$uri->getQuery(),
			'fragment'=>$uri->getFragment()
		);
		
		/*
			Get Minimal headers.
		*/
		$_headers = array(
			"referer"=>$request->getHeaderLine('referer'),
			"uagent"=>$request->getHeaderLine('user-agent'),
			"dnt"=>$request->hasHeader('dnt')
		);
		
		
		//$contentType = $request->getContentType();
		
		//$headers = $request->getHeaders();
		
		
		
		
		
		/* 
			Content negotiation.  
		*/
		$responseType = 'json';
		if ($request->hasHeader('accept')) {
			if(strpos($request->getHeaderLine('accept'),'text/html')!==false ){
				$responseType = 'html';
			}
		}
		
		
		
		$templateVars = array(
			'error'=>$this->ExceptionToArray($error),
			'debug'=>(int)$this->debug,
			'clientid'=>$this->sfe->clientid
		);
		
		
		$jsonError["code"]		= $error->getCode;
		$jsonError["status"]	= "Fatal Exception.";
		$jsonError["statusmsg"]	= $error->getMessage();
		$jsonError["data"] = $this->ExceptionToArray($error);
		
		
		
		
		if($error->getCode()==404){
			$jsonError["status"]	= "Not found.";
			if($responseType=='html'){
				return $this->view->render($response,'HTTP404.html',$templateVars)->withStatus(404);
			}else{
				return $response->withStatus(404)->withJson($jsonError);
			}
		}
		
		if($responseType=='html'){
			return $this->view->render($response,'HTTP500.html',$templateVars)->withStatus(500);
		}else{
			return $response->withStatus(500)->withJson($jsonError);
		}
		
		
		//return $response->withStatus(500)->withHeader('Content-Type', 'text/html')->write('errorHandler <pre>'.$error);
	}
	
	public function phpErrorHandler($request, $response, $error){
		
		//return function ($request, $response, $error) use ($c) {
		return $response->withStatus(500)->withHeader('Content-Type', 'text/html')->write('phpErrorHandler <pre>'.$error);
		//};
	}
	
	
	
	
	/*
		
		this is the Exception for when something goes wrong on existing endpoints,
		
	*/
	function EndpointException( ServerRequestInterface $request, ResponseInterface $response,$e){
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*
		
		Static function for pre-app initialized errors.
	\Botnyx\Sfe\Shared\Exception::configurationException
	*/
	
	static function kill($html){
		header('HTTP/1.0 500 Internal Server Error',true,500);
		die($html);
	}
	
	static function configurationException ($e){
		return \Botnyx\Sfe\Shared\Exception::fatalExceptionHtml('A configuration error occured.',$e->getMessage());
	}
	
	static function unknownException ($e){
		return \Botnyx\Sfe\Shared\Exception::fatalExceptionHtml('A unknown error occured.',$e->getMessage());
	}
	
	static function fatalExceptionHtml( $shortmessage='',$hint=''){
		
		return '<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Simple HttpErrorPages | MIT License | https://github.com/AndiDittrich/HttpErrorPages -->
    <meta charset="utf-8" /><meta http-equiv="X-UA-Compatible" content="IE=edge" /><meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fatal Exception | 500 - Webservice currently unavailable</title>
    <style type="text/css">
    /*! normalize.css v5.0.0 | MIT License | github.com/necolas/normalize.css */html{font-family:sans-serif;line-height:1.15;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}article,aside,footer,header,nav,section{display:block}h1{font-size:2em;margin:.67em 0}figcaption,figure,main{display:block}figure{margin:1em 40px}hr{box-sizing:content-box;height:0;overflow:visible}pre{font-family:monospace,monospace;font-size:1em}a{background-color:transparent;-webkit-text-decoration-skip:objects}a:active,a:hover{outline-width:0}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:inherit}b,strong{font-weight:bolder}code,kbd,samp{font-family:monospace,monospace;font-size:1em}dfn{font-style:italic}mark{background-color:#ff0;color:#000}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-.25em}sup{top:-.5em}audio,video{display:inline-block}audio:not([controls]){display:none;height:0}img{border-style:none}svg:not(:root){overflow:hidden}button,input,optgroup,select,textarea{font-family:sans-serif;font-size:100%;line-height:1.15;margin:0}button,input{overflow:visible}button,select{text-transform:none}[type=reset],[type=submit],button,html [type=button]{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{border-style:none;padding:0}[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring,button:-moz-focusring{outline:1px dotted ButtonText}fieldset{border:1px solid silver;margin:0 2px;padding:.35em .625em .75em}legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}progress{display:inline-block;vertical-align:baseline}textarea{overflow:auto}[type=checkbox],[type=radio]{box-sizing:border-box;padding:0}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-cancel-button,[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}details,menu{display:block}summary{display:list-item}canvas{display:inline-block}template{display:none}[hidden]{display:none}/*! Simple HttpErrorPages | MIT X11 License | https://github.com/AndiDittrich/HttpErrorPages */body,html{
	width: 100%;height: 100%;background-color: #470000}body{color:#fff;text-align:center;text-shadow:0 2px 4px rgba(0,0,0,.5);padding:0;min-height:100%;-webkit-box-shadow:inset 0 0 100px rgba(0,0,0,.8);box-shadow:inset 0 0 100px rgba(0,0,0,.8);display:table;font-family:"Open Sans",Arial,sans-serif}h1{font-family:inherit;font-weight:500;line-height:1.1;color:inherit;font-size:36px}h1 small{font-size:68%;font-weight:400;line-height:1;color:#777}a{text-decoration:none;color:#fff;font-size:inherit;border-bottom:dotted 1px #707070}.lead{color:silver;font-size:21px;line-height:1.4}.cover{display:table-cell;vertical-align:middle;padding:0 20px}footer{position:fixed;width:100%;height:40px;left:0;bottom:0;color:#a0a0a0;font-size:14px}
    </style>
</head>
<body>
    <div class="cover"><h1>Webservice currently unavailable <small>Error 500</small></h1><p class="lead">A Fatal Exception was encountered.<br />'.$shortmessage.'</p></div>
    <!--- '.$hint.'  --->
</body>
</html>';
	}
}