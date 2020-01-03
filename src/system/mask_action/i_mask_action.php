<?php

namespace Nuclear\system\mask_action;

interface i_mask_action{
	

	/**
	 * Busca existencia da máscara para a action
	 * @param string $rota
	 * @return boolean
	 */
	public function existMask($rota);

	/**
	 * Executa action com até 7 variáveis de entrada
	 * @param object $controller
	 * @param array $rotas
	 * @param string $mask
	 * @return boolean
	 */
	public function exec_action($controller, $rotas, $mask);

}

?>