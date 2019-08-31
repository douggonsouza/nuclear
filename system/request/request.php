<?php
/**
 * Orbe Framework
 * @copyright Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @license Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @author Gonçalves Informática <douggonsouza@gmail.com>
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
*/

namespace vendor\douggs\nuclear\system\request;

use vendor\douggs\nuclear\configs\cfg;

class request{

    public $url            = '';
    public $http           = '';
    public $query_string   = '';
    public $subdomain      = '';
    public $extensao       = '';
    public $request        = '';

    public $api            = '';
    public $controller     = '';
    public $management     = '';
    public $model          = '';
    public $script         = '';
    public $media          = '';
    public $layout         = '';
    public $view           = '';
    public $action         = '';
    public $baseController = '';
    public $arguments      = [];
    public $filename       = '';

    public $localApi        = '';
    public $localSubdomain  = '';
    public $localController = '';
    public $localModel      = '';
    public $localView       = '';
    public $localFile       = '';
    public $notFound        = '';

    public $isCtrl         = false;
    public $isError        = true;
    public $isDirect       = false;
    public $rootBase       = '';
    public $fileLayout     = '';

    public function __construct()
    {
        $this->subdomain      = cfg::rescue('root')['default_subdomain'];
        $this->action         = cfg::rescue('root')['default_action'];
        $this->baseController = cfg::rescue('root')['default_controller'];
        $this->rootBase       = cfg::rescue('root')['base'];
        $this->notFound       = cfg::rescue('root')['default_not_found'];
    }

    /**
     * Recebe a url
     *
     * @param string $url
     * @return void
     */
    public function addUrl($url = null)
    {
		$this->url = (isset($url) && strlen($url) > 0)? $url: $this->url;
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
            DRT.$this->rootBase.DS.$this->subdomain.DS.'controllers'
        );
        $this->controller = $this->separatorForNamespace(
            $this->rootBase.DS.$this->subdomain.DS.'controllers'.DS.$defaultController
        );
        $this->model = $this->separatorForNamespace(
            $this->rootBase.DS.$this->subdomain.DS.'models'.DS.$defaultController
        );
        $this->localModel = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'models'
        );
        $this->localView = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'views'
        );
        $this->view = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'views'.DS.$defaultController
        );
        $this->management = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'managements'
        );
        $this->script = $this->separatorForDir(
            $this->rootBase.DS.$this->subdomain.DS.'scripts'
        );
        $this->media = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'media'
        );
        $this->layout = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'layouts'
        );
        $this->fileLayout = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'layouts/'.$fileLayout
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
     * Direct request
     *
     * @param boolean $direct
     * @return void
     */
    private function directRequest($direct = false, $fileLayout = null)
    {
        if(!$direct)
            return $direct;

        $this->localSubdomain = $this->separatorForDir(
            DS.$this->subdomain
        );
        $this->localFile = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.$this->request
        );
        $this->localController = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'controllers'
        );
        $this->localModel = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'models'
        );
        $this->localView = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'views'
        );
        $this->management = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'managements'
        );
        $this->script = $this->separatorForDir(
            $this->rootBase.DS.$this->subdomain.DS.'scripts'
        );
        $this->media = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'media'
        );
        $this->layout = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'layouts'
        );
        $this->fileLayout = $this->separatorForDir(
            DRT.$this->rootBase.DS.$this->subdomain.DS.'layouts/'.$fileLayout
        );
        if($this->request !== '/'){
            if(!$this->searchFilename($this->request)){
                $this->isError = true;
                return $direct;
            }    
        }
        $this->isError = false;
        return $direct;
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

    public function clearDirectUrl($url)
    {
        $this->query_string = '';
        $request = $url;
        if(isset($url) && strlen($url) > 0){
            $request = $this->clearQueryString($url);
            $request = $this->clearHost($request);
            $this->extension($request);
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

    public function extension($request)
    {
        $match   = [];
        if(!isset($request) || strlen($request) == 0)
            return ''; 
        preg_match('/\.\w+$/', $request, $match, PREG_OFFSET_CAPTURE);
        if(!empty($match)){
            $this->extensao = $match[0][0];
        }
        return $this->extensao;
    }

    public function separatorForDir($local)
    {
        if(isset($local) && strlen($local) > 0){
            return str_replace(
                array('/','//','\\','\\\\'),
                '/',
                $local);
        }
        return null;
    }

    public function separatorForNamespace($local)
    {
        if(isset($local) && strlen($local) > 0){
            return str_replace(
                array('/','//','\\','\\\\'),
                '\\',
                $local);
        }
        return null;
    }

    /**
     * Undocumented function
     *
     * @param array $wall
     * @return void
     */
    public function searchRoutes($request)
    {
        $wall = [];

        if(!isset($request) || strlen($request) == 0)
            return false;
        $wall = explode(DS,$request);

        $arrCount = 0;
        if(!empty($wall)){
            $clearWalls = array_filter($wall);
            $controller = $this->rootBase.DS.$this->subdomain.DS.'controllers'.DS.$this->baseController;
            $this->controller = $this->separatorForNamespace(
                $controller
            );
            $this->localController = $this->separatorForDir(
                DRT.$controller
            );
            $model = $this->rootBase.DS.$this->subdomain.DS.'models';
            $this->model = $this->separatorForNamespace(
                $model
            );
            $this->localModel = $this->separatorForDir(
                DRT.$model
            );
            $view = $this->rootBase.DS.$this->subdomain.DS.'views'.DS.$this->baseController;
            $this->view = $this->separatorForNamespace(
                $view
            );
            $this->localView = $this->separatorForDir(
                DRT.$view
            );
            $fileLayout = $this->rootBase.DS.$this->subdomain.DS.'layouts/'.cfg::rescue('root')['default_layout'];
            $this->fileLayout = $this->separatorForDir(
                DRT.$fileLayout
            );
            $this->isCtrl = true;
            foreach($clearWalls as $value){
                switch($arrCount){
                    case 0:
                        $controller = $this->rootBase.DS.$this->subdomain.DS.'controllers'.DS.$value;
                        $this->controller = $this->separatorForNamespace(
                            $controller
                        );
                        $this->localController = $this->separatorForDir(
                            DRT.$controller
                        );
                        $model = $this->rootBase.DS.$this->subdomain.DS.'models'.DS.$value;
                        $this->model = $this->separatorForNamespace(
                            $model
                        );
                        $this->localModel = $this->separatorForDir(
                            DRT.$model
                        );
                        $view = $this->rootBase.DS.$this->subdomain.DS.'views'.DS.$value;
                        $this->view = $this->separatorForNamespace(
                            $view
                        );
                        $this->localView = $this->separatorForDir(
                            DRT.$view
                        );
                        $fileLayout = $this->rootBase.DS.$this->subdomain.DS.'layouts/'.cfg::rescue('root')['default_layout'];
                        $this->fileLayout = $this->separatorForDir(
                            DRT.$fileLayout
                        );
                        $this->isCtrl     = true;
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
        }
        return true;
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
}