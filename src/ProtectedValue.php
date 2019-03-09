<?php

namespace Botnyx\Sfe\Shared;


class ProtectedValue {
    
	private $prop;

    public function __construct($val) {
        $this->prop = $val;
    }
	
	public function __toString(){
		return $this->prop;
	}
	
	public function __debugInfo() {
        return [
            'value' => "*** PROTECTED",
        ];
    }
}
