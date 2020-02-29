<?php

namespace Nuclear\system;

use Nuclear\system\control\action;
use Nuclear\configs\cfg;
use Nuclear\app\orbe;
   
class Calls
{
	private $controller = null;
	private $rota       = null;
	private $folderMVC  = '';
	private $notFound   = null;
	protected $pathController = null;

	public function init($file, $action, $arguments)
	{
		// arquivo existe
		$file = $this->replaceDir($file.'.php');
		if(!file_exists($file)){
			throw new \Exception("Não foi possível iniciar a controller.");
		}
		
		$this->act = new $action();
            
		// aplica arguments as actions
		$this->applyArguments(
			$this->act,
			$arguments
		);      
		return true;
	}

	/**
	 * Aplica argumentos nas actions
	 *
	 * @param string $controller
	 * @param string $action
	 * @param string $arguments
	 * @return void
	 */
	private function applyArguments($controller, $arguments)
	{
		// disparo anterior a ação
		$controller->__before();

		$countArguments = count($arguments);
		switch($countArguments){
			case 0:
				$controller->main();
			break;
			case 1:
				$controller->main($arguments[0]);
			break;
			case 2:
				$controller->main(
					$arguments[0],
					$arguments[1]
				);
			break;
			case 3:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2]
				);
			break;
			case 4:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2],
					$arguments[3]
				);
			break;
			case 5:
				$controller->main(
					$arguments[0],
					$arguments[1],
					$arguments[2],
					$arguments[3],
					$arguments[4],
					$arguments[5]
				);
			break;
			case 6:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6]
			);
			break;
			case 7:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7]
			);
			break;
			case 8:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7],
				$arguments[8]
			);
			break;
			case 9:
			$controller->main(
				$arguments[0],
				$arguments[1],
				$arguments[2],
				$arguments[3],
				$arguments[4],
				$arguments[5],
				$arguments[6],
				$arguments[7],
				$arguments[8],
				$arguments[9]
			);
			break;
		}

		// disparo anterior a aÃ§Ã£o
		$controller->__after();
	}
	
	private function replaceDir($local)
	{
		return str_replace(
			array('/','//','\\','\\\\'),
			'/',   
			$local);
	}

}