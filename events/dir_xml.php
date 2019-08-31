<?php
/**
 * Events
 *
 * Gerenciador de eventos de chamadas assincronas.
 * @version 1.00000.00.00000
 * @copyright De Souza Informática - 2016
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
 *
 */

namespace vendor\douggs\nuclear\events;


class dir_xml{
	
	/**
	 * Lista conteÃºdo de um diretÃ³rio
	 * @param string $local
	 * @return boolean
	 */
	public function folder_contents($local){
		
		// existe pasta
		if(is_dir($local)){
	
			return scandir($local,1);
		}
		return false;
	}	
}

?>