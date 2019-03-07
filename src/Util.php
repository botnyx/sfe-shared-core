<?php

namespace Botnyx\Sfe\Shared;

class Util {
	
	/* Cookie helper func. */
	public function getCookieValue(\Slim\Http\Request $request, $cookieName)
	{
		$cookies = $request->getCookieParams();
		return isset($cookies[$cookieName]) ? $cookies[$cookieName] : null;
	}
	
}
	
	