<?php

namespace vendor\nuclear\app;


class propertys extends \stdClass{


    public function __sleep() {
        return array_keys((get_object_vars($this)));
    }


	public function add($name, $value){
		if(isset($name) && isset($value) && strlen($value) > 0)
			$this->{$name} = $value;
		return $this;
	}


	public function rescue($name){

		return $this->{$name};
	}

}


?>