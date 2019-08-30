<?php

namespace vendor\douggs\nuclear\system\mask_action;

use vendor\douggs\nuclear\system\mask_action\i_mask_action;

class mask_action implements i_mask_action{


	/* propretys */
	private $masks = null;


	public function __construct(){

		$this->masks = include('mask/masks.php');

	}


	public function existMask($rota){

		if(is_string($rota) && strlen($rota) > 0){

			if(array_key_exists($rota, $this->masks)){
				return $this->masks[$rota];
			}
		}
		return false;
	}

	/**
	 * Executa action com até 7 variáveis de entrada
	 * @param object $controller
	 * @param array $rotas
	 * @param string $mask
	 * @return boolean
	 */
	public function exec_action($controller, $rotas, $mask){

		if(isset($controller) & isset($rotas) && isset($mask)){

			if(is_object($controller) && is_array($rotas) && is_string($mask)){

				$vars = explode(',', $mask);
				switch(count($vars)){
					case 1:
						return $controller->{$rotas['action']}($rotas[$vars[0]]);
						break;
					case 2:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]]);
						break;
					case 3:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]],$rotas[$vars[2]]);
						break;
					case 4:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]],$rotas[$vars[2]],$rotas[$vars[3]]);
						break;
					case 5:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]],$rotas[$vars[2]],$rotas[$vars[3]],$rotas[$vars[4]]);
						break;
					case 6:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]],$rotas[$vars[2]],$rotas[$vars[3]],$rotas[$vars[4]],$rotas[$vars[5]]);
						break;
					case 7:
						return $controller->{$rotas['action']}($rotas[$vars[0]],$rotas[$vars[1]],$rotas[$vars[2]],$rotas[$vars[3]],$rotas[$vars[4]],$rotas[$vars[5]],$rotas[$vars[6]]);
						break;
					default: break;
				}

			}
		}
		return false;
	}
}

?>