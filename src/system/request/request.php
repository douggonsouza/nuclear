<?php

namespace Nuclear\system\request;

use Nuclear\configs\cfg;

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
    public $crtl           = '';
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
     * Direct request
     *
     * @param boolean $direct
     * @return void
     */
    private function directRequest($direct = false, $fileLayout = null)
    {
        if(!$direct)
            return $direct;

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