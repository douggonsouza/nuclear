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

use vendor\douggs\nuclear\configs\cfg;


class assync{

	private $param         = null;
	private $result_pickup = null;
	private $url_api       = null;
	private $curl          = null;


	public function call($url, $param = null){
		
		// existe parametro
		$this->param = (isset($param))? serialize($param): null;

		// existe url
		if(isset($url) && strlen($url) > 0){
			$this->url_api = $url;

			ignore_user_abort(1); // run script in background
			set_time_limit(0); // run script forever
			
			// inicia curl
			$this->curl = curl_init(cfg::getCfg('general')['BASEHOST'].$this->url_api);
			if(isset($this->curl)){
				curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true );
				curl_setopt($this->curl, CURLOPT_BINARYTRANSFER, true);
				curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($this->curl, CURLOPT_FRESH_CONNECT, true);
				curl_setopt($this->curl, CURLOPT_TIMEOUT_MS, 1);
				curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->param);
				$result = curl_exec($this->curl);
				curl_close($this->curl);
			}
		}
	}
}

?>