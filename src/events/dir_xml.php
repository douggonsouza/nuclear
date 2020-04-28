<?php

namespace Nuclear\events;


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