<?php

namespace Nuclear\system;

use Nuclear\system\Routers;
use Nuclear\system\Call;
use Nuclear\system\nuclear;
use Nuclear\configs\cfg;
use Nuclear\system\control\action;

class Response extends nuclear
{
	protected $routers = null;
	protected $url     = null;

	/**
	 * Direciona requisição para o action do controller
	 * @param string $base
	 * @param string $url
	 */
	public function routes($url = null)
	{
		$this->routers = new Routers();

		// carrega as rotas
		$this->routers->routers($url);

        return true;
	}
}

