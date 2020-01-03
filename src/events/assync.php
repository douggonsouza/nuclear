<?php 

namespace Nuclear\events;

use Nuclear\configs\cfg;


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
			$this->curl = curl_init(cfg::rescue('general')['BASEHOST'].$this->url_api);
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