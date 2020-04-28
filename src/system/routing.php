<?php

namespace Nuclear\system;

use Nuclear\system\control\action;
use Nuclear\system\request\request;
use Nuclear\loader\loader;
use Nuclear\configs\cfg;
use Nuclear\app\orbe;
use Nuclear\system\view\display;
   
class routing
{
	  
	private $controller = null;
	private $rota       = null;
	private $folderMVC  = '';
	private $notFound   = null;
	protected $pathController = null;
   
	/**
	 * Evento construtor da classe
	 * @param string $url
	 */
	public function __construct()
	{
		$this->request = new request();
		Loader::start(DR);
	}    
	  
	/**
	 * Direciona requisiÃ§Ã£o para o action do controller
	 * @param string $base
	 * @param string $url
	 */
	public function routes($url = null)
	{
		// verifica existencia de extensão
		$this->existExtension($this->request->extension($url));

		// carrega as rotas e guarda na controller
		act::addRequest(($this->request = $this->request->urlRoutes($url)));
		if($this->request->isError)
			throw new \Exception('Erro ao carregar rotas na controller');
		
		// carrega endereço de requisição direta
		$this->requestDirect(
			$this->request->localFile,
			$this->request->isDirect
		);

		$this->pathController = $this->replaceDir(
			$this->request->localController.$this->request->action.'.php'
		);
		if(!file_exists($this->pathController))
			throw new \Exception("Arquivo da classe não encontrado.");       
	
		// inicia classe
		$fileAct = $this->replaceNamespace(
			$this->request->controller.$this->request->action
		);
		if(!$this->initController($fileAct)){
			header("location:".$this->request->notFound);
        	return false;
		}

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

	private function existExtension($extension)
	{
		if(!isset($extension) || empty($extension))
			return;

		$extensionClear = str_replace(
		cfg::rescue('root')['file_job'],
		'',
		$extension);
		if($extensionClear !== "")
			$this->request->isDirect = true;
	}

	private function isDirect($localFile, $isDirect = false)
	{
		if($isDirect){
			$this->pathController = $this->replaceDir($localFile);
		} 
	}

	private function renderDirect($isDirect = false)
	{
		if(!$isDirect)
			return;

		$display = new display();
		$display->render($this->request);
		die(); 
	}

	private function replaceNamespace($local)
	{
		return str_replace(
			array('/','//','\\','\\\\'),
			'\\',   
			$local);
	}

	private function replaceDir($local)
	{
		return str_replace(
			array('/','//','\\','\\\\'),
			'/',   
			$local);
	}

	private function initController($controller)
	{
		$this->act = new $controller;
		                           
		if(!isset($this->act))
			throw new \Exception("Não foi possível iniciar a controller.");
            
		// seta o template
		$this->act->setTemplate($this->request->action);
            
		// aplica arguments as actions
		$this->applyArguments(
			$this->act,
			$this->request->arguments
		);      
		return true;
	}

	private function requestDirect($local, $isDirect)
	{
		$this->isDirect($local, $isDirect);
		$this->renderDirect($isDirect);
	}
}