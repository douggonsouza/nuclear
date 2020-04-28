<?php

namespace Nuclear\system\routers;

use Nuclear\system\control\action;
use Nuclear\system\request\request;
use Nuclear\system\routers\Router;
use Nuclear\system\routers\routerInterface;
use Nuclear\system\nuclear;
use Nuclear\loader\loader;
use Nuclear\configs\cfg;
use Nuclear\app\orbe;
use Nuclear\system\view\display;
   
class DirectRouter extends nuclear implements routerInterface
{

    protected $url           = null;
    protected $defaultLayout = null;
    protected $router        = null;
    protected $query_string  = null;
    protected $request       = null;

    /**
     * Regra de negócio para a renderização da rota
     *
     * @return bool
     */
    public function render(): bool
    {
        $this->router = new Router(cfg::rescue('root'),false);

        // limpa url
        $this->clearUrl($this->url);

        // carrega rotas
        $this->routings(true, $this->defaultLayout);

        // rederiza requisição
        $this->rendering();
    }
    
    public function clearUrl()
    {
        $this->query_string = '';
        if(isset($this->url) && strlen($this->url) > 0){
            $this->request = $this->clearQueryString($this->url);
            $this->request = $this->clearHost($this->request);
        }
        return $this->request;
    }

    public function clearQueryString($request)
    {
        if(!isset($request) || strlen($request) == 0)
            return null;

        if(!isset($_SERVER['QUERY_STRING']) || strlen($_SERVER['QUERY_STRING']) == 0)
            return $this->request;
        $this->query_string = $_SERVER['QUERY_STRING'];
        return str_replace('?'.$this->query_string,'', $this->request);
    }

    public function clearHost($request)
    {
        if(!isset($request) || strlen($request) == 0)
            return null;
        $this->http = $_SERVER['HTTP_HOST'];
        return substr(
            $this->request,
            stripos($this->request,$this->http)+strlen($this->http),
            strlen($this->request)
        );
    }

	private function rendering()
	{
		$display = new display();
		$display->render($this->request);
		die(); 
	}

	/**
     * Carrega as rotas diretas
     *
     * @return bool
     */
    private function routings()
    {
        $defaultController = cfg::rescue('root')['default_controller'];

        $this->localSubdomain = $this->separatorForDir(
            DS.$this->subdomain
        );
        $this->localFile = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.$this->request
        );
        $this->localController = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'controllers'
        );
        $this->localModel = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'models'
        );
        $this->localView = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'views'
        );
        $this->management = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'managements'
        );
        $this->script = $this->separatorForDir(
            $this->rootBase.DS.$defaultController.DS.'scripts'
        );
        $this->media = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'media'
        );
        $this->layout = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts'
        );
        $this->fileLayout = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts/'.$fileLayout
        );
        if($this->request !== '/'){
            if(!$this->searchFilename($this->request)){
                $this->isError = true;
                return $direct;
            }    
        }
        $this->isError = false;
        return true;
	}
	
	// public function searchFilename($request)
    // {
    //     $wall = [];
    //     if(!isset($request) || strlen($request) == 0)
    //         return false;
    //     $atualRequest = str_replace(
    //         $this->extensao,
    //         '',
    //         $request);
    //     $wall = explode(DS,$atualRequest);
    //     if(!empty($wall)){
    //         $arrCount = 0;
    //         $wall = array_filter($wall);
    //         $wall = array_reverse($wall);
    //         foreach($wall as $value){
    //             switch($arrCount){
    //                 case 0:
    //                     $this->filename = $value;
    //                 break;
    //             }
    //             $arrCount++;
    //         }
    //     }
    //     return true;
    // }

        /**
     * Undocumented function
     *
     * @param array $wall
     * @return void
     */
    // public function searchRoutes($request)
    // {
    //     $wall = explode(DS,$request);
    //     $defaultController = cfg::rescue('root')['default_controller'];
    //     $defaultAction     = cfg::rescue('root')['default_action'];

    //     if(!isset($request))
    //         return false;
        
    //     $arrCount = 0;
    //     if(empty($request) || $request == '/'){
    //         $this->controller = $this->separatorForNamespace(
    //             $this->rootBase.DS.$defaultController.DS.'controllers'.DS.$defaultController.DS
    //         );
    //         $this->localController = $this->separatorForDir(
    //             DRT.DS.$this->controller
    //         );
    //         $this->model = $this->separatorForNamespace(
    //             $this->rootBase.DS.$defaultController.DS.'models'.DS
    //         );
    //         $this->localModel = $this->separatorForDir(
    //             DRT.DS.$this->model
    //         );
    //         $this->view = $this->separatorForNamespace(
    //             $this->rootBase.DS.$defaultController.DS.'views'.DS.$defaultController.DS
    //         );
    //         $this->localView = $this->separatorForDir(
    //             DRT.DS.$this->view
    //         );
    //         $this->fileLayout = $this->separatorForDir(
    //             DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts/'.cfg::rescue('root')['default_layout']
    //         );
    //         $this->crtl   = $defaultController;
    //         $this->isCtrl = true;
    //         $this->action = $defaultAction;
    //         return true;
    //     }
    //     $clearWalls = array_filter($wall);
    //     foreach($clearWalls as $value){
    //         switch($arrCount){
    //             case 0:
    //                 $this->controller = $this->separatorForNamespace(
    //                     $this->rootBase.DS.$defaultController.DS.'controllers'.DS.$value.DS
    //                 );
    //                 $this->localController = $this->separatorForDir(
    //                     DRT.DS.$this->controller
    //                 );
    //                 $this->model = $this->separatorForNamespace(
    //                     $this->rootBase.DS.$defaultController.DS.'models'.DS
    //                 );
    //                 $this->localModel = $this->separatorForDir(
    //                     DRT.DS.$this->model
    //                 );
    //                 $this->view = $this->separatorForNamespace(
    //                     $this->rootBase.DS.$defaultController.DS.'views'.DS.$value.DS
    //                 );
    //                 $this->localView = $this->separatorForDir(
    //                     DRT.DS.$this->view
    //                 );
    //                 $this->fileLayout = $this->separatorForDir(
    //                     DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts/'.cfg::rescue('root')['default_layout']
    //                 );
    //                 $this->crtl = $value;
    //                 $this->isCtrl = true;
    //             break;
    //             case 1;
    //                 $this->action = $value;
    //             break;
    //             default:
    //                 $this->arguments[] = $value;
    //             break;
    //         }
    //         $arrCount++;
    //     }
    //     return true;
    // }
}