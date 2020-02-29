<?php

namespace Nuclear\system\routers;

use Nuclear\system\control\action;
use Nuclear\system\request\request;
use Nuclear\system\routers\Router;
use Nuclear\system\nuclear;
use Nuclear\loader\loader;
use Nuclear\configs\cfg;
use Nuclear\app\orbe;
use Nuclear\system\view\display;
   
class SubdomainRouter extends nuclear
{

    protected $router = null;
    
    public function __construct(){
        $this->router = new Router();
    }


    /**
     * Undocumented function
     *
     * @param string $url
     * @return string
     */
    public function clearUrl($url)
    {
        $this->query_string = '';
        $request = $url;
        if(isset($url) && strlen($url) > 0){
            $request = $this->clearQueryString($url);
            $request = $this->clearHost($request);
            $this->extension($request);
            $request = $this->clearExtension($request);
        }
        return $request;
    }

    public function clearQueryString($request)
    {
        if(!isset($request) || strlen($request) == 0)
            return null;
        if(!isset($_SERVER['QUERY_STRING']) || strlen($_SERVER['QUERY_STRING']) == 0)
            return $request;
        $this->query_string = $_SERVER['QUERY_STRING'];
        return str_replace('?'.$this->query_string,'',$request);
    }

    public function clearHost($request)
    {
        if(!isset($request) || strlen($request) == 0)
            return null;
        $this->http = $_SERVER['HTTP_HOST'];
        return substr(
            $request,
            stripos($request,$this->http)+strlen($this->http),
            strlen($request)
        );
    }

    public function clearExtension($request)
    {
        if(!isset($this->extensao) || strlen($this->extensao) == 0)
            return $request;
        return str_replace($this->extensao,'',$request);
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

	private function requestDirect($local, $isDirect)
	{
		$this->isDirect($local, $isDirect);
		$this->renderDirect($isDirect);
    }
    
        /**
     * Indirect request
     *
     * @return void
     */
    private function indirectRequest($fileLayout = null)
    {
        $defaultController = cfg::rescue('root')['default_controller'];

        $this->localSubdomain = $this->separatorForDir(
            DS.$this->subdomain
        );
        $this->localController = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'controllers'
        );
        $this->controller = $this->separatorForNamespace(
            $this->rootBase.DS.$defaultController.DS.'controllers'
        );
        $this->model = $this->separatorForNamespace(
            $this->rootBase.DS.$defaultController.DS.'models'
        );
        $this->localModel = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'models'
        );
        $this->localView = $this->separatorForDir(
            DRT.DS.$this->rootBase.DS.$defaultController.DS.'views'
        );
        $this->view = $this->separatorForDir(
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
        // if($this->request !== '/'){
            if(!$this->searchRoutes($this->request)){
                $this->isError = true;
                return;
            }    
        // }
        $this->isError = false;
    }

    /**
      * Define a rota para a controller, action e variaveis
      *
      * @param string $url
      * @return array
      */
      public function urlRoutes($url)
      {   
          $this->addUrl($url);
  
          $fileLayout  = cfg::rescue('root')['default_layout'];
  
          $defaultSubdomain = cfg::rescue('root')['default_subdomain'];
          if(!isset($defaultSubdomain))
              throw new \Exception('Not found default subdomain');
          $this->subdomain($this->url,$defaultSubdomain);
  
          if(!isset($this->subdomain) || (!isset($this->url) || strlen($this->url) == 0))
              throw new \Exception('Not found routes.');
  
          $this->request  = $this->clearUrl($this->url);
          $this->isCtrl   = false;
  
          // Is direct request
          if($this->directRequest($this->isDirect,$fileLayout))
              return $this;
          
          // Is indirect request
          $this->indirectRequest($fileLayout);
          return $this;
      }
    
        
    /**
     * Extrai o subdominio
     *
     * @param string $base
     * @return bool
     */
    public function subdomain($url, $default = 'home')
    {
        $arrUrl = [];
        if(isset($url) && is_string($url))
            $arrUrl = explode('.',$url);
        $subdomain = strtolower($arrUrl[0]);
        if(isset($arrUrl[0])){
            if(is_dir(DRT.DS.'root'.DS.$subdomain)){
                $this->subdomain = $subdomain;
                return true;
            }
            $this->subdomain = $default;
        }
        return false;
    }
	
	public function searchFilename($request)
    {
        $wall = [];
        if(!isset($request) || strlen($request) == 0)
            return false;
        $atualRequest = str_replace(
            $this->extensao,
            '',
            $request);
        $wall = explode(DS,$atualRequest);
        if(!empty($wall)){
            $arrCount = 0;
            $wall = array_filter($wall);
            $wall = array_reverse($wall);
            foreach($wall as $value){
                switch($arrCount){
                    case 0:
                        $this->filename = $value;
                    break;
                }
                $arrCount++;
            }
        }
        return true;
    }

    /**
     * Undocumented function
     *
     * @param array $wall
     * @return void
     */
    public function searchRoutes($request)
    {
        $wall = explode(DS,$request);
        $defaultController = cfg::rescue('root')['default_controller'];
        $defaultAction     = cfg::rescue('root')['default_action'];

        if(!isset($request))
            return false;
        
        $arrCount = 0;
        if(empty($request) || $request == '/'){
            $this->controller = $this->separatorForNamespace(
                $this->rootBase.DS.$defaultController.DS.'controllers'.DS.$defaultController.DS
            );
            $this->localController = $this->separatorForDir(
                DRT.DS.$this->controller
            );
            $this->model = $this->separatorForNamespace(
                $this->rootBase.DS.$defaultController.DS.'models'.DS
            );
            $this->localModel = $this->separatorForDir(
                DRT.DS.$this->model
            );
            $this->view = $this->separatorForNamespace(
                $this->rootBase.DS.$defaultController.DS.'views'.DS.$defaultController.DS
            );
            $this->localView = $this->separatorForDir(
                DRT.DS.$this->view
            );
            $this->fileLayout = $this->separatorForDir(
                DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts/'.cfg::rescue('root')['default_layout']
            );
            $this->crtl   = $defaultController;
            $this->isCtrl = true;
            $this->action = $defaultAction;
            return true;
        }
        $clearWalls = array_filter($wall);
        foreach($clearWalls as $value){
            switch($arrCount){
                case 0:
                    $this->controller = $this->separatorForNamespace(
                        $this->rootBase.DS.$defaultController.DS.'controllers'.DS.$value.DS
                    );
                    $this->localController = $this->separatorForDir(
                        DRT.DS.$this->controller
                    );
                    $this->model = $this->separatorForNamespace(
                        $this->rootBase.DS.$defaultController.DS.'models'.DS
                    );
                    $this->localModel = $this->separatorForDir(
                        DRT.DS.$this->model
                    );
                    $this->view = $this->separatorForNamespace(
                        $this->rootBase.DS.$defaultController.DS.'views'.DS.$value.DS
                    );
                    $this->localView = $this->separatorForDir(
                        DRT.DS.$this->view
                    );
                    $this->fileLayout = $this->separatorForDir(
                        DRT.DS.$this->rootBase.DS.$defaultController.DS.'layouts/'.cfg::rescue('root')['default_layout']
                    );
                    $this->crtl = $value;
                    $this->isCtrl = true;
                break;
                case 1;
                    $this->action = $value;
                break;
                default:
                    $this->arguments[] = $value;
                break;
            }
            $arrCount++;
        }
        return true;
    }
}