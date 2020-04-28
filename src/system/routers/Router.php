<?php

namespace Nuclear\system\routers;

use Nuclear\configs\cfg;
use Nuclear\system\nuclear;

class Router extends nuclear
{
    // variáveis iniciais
    public $url            = null;
    public $http           = null;
    public $extensao       = null; 
    public $request        = null; 
    public $query_string   = null;
    public $base           = null;
    public $subdomain      = null;
    public $controller     = null;
    public $action         = null;
    public $layout         = null;
    public $namespace      = null;

    public $baseBase           = null;
    public $baseSubdomain      = null;
    public $baseLayout         = null;
    public $baseController     = null;
    public $baseManagement     = null;
    public $baseModel          = null;
    public $baseScript         = null;
    public $baseMedia          = null;
    public $baseView           = null;

    public $baseUrlBase           = null;
    public $baseUrlSubdomain      = null;
    public $baseUrlLayout         = null;
    public $baseUrlController     = null;
    public $baseUrlManagement     = null;
    public $baseUrlModel          = null;
    public $baseUrlScript         = null;
    public $baseUrlMedia          = null;
    public $baseUrlView           = null;

    public $namespaceControllers  = null;
    public $namespaceModels       = null;
    public $namespaceManagements  = null;


    public $atualBase           = null;
    public $atualSubdomain      = null;
    public $atualLayout         = null;
    public $atualController     = null;
    public $atualAction         = null;
    public $atualManagement     = null;
    public $atualModel          = null;
    public $atualScript         = null;
    public $atualMedia          = null;
    public $atualView           = null;

    public $atualUrlBase           = null;
    public $atualUrlSubdomain      = null;
    public $atualUrlLayout         = null;
    public $atualUrlController     = null;
    public $atualUrlAction         = null;
    public $atualUrlManagement     = null;
    public $atualUrlModel          = null;
    public $atualUrlScript         = null;
    public $atualUrlMedia          = null;
    public $atualUrlView           = null;

    public $atualArguments  = [];

    public $isController = false;
    public $isError      = true;

    public function __construct($url, $isController = false)
    {
        $this->url          = $url;
        $this->isController = $isController;   
        $this->base         = cfg::rescue('root')['base'];
        $this->layout       = cfg::rescue('root')['default_layout'];
        $this->controller   = cfg::rescue('root')['default_controller'];
        $this->subdomain    = cfg::rescue('root')['default_subdomain'];
        $this->action       = cfg::rescue('root')['default_action'];
    }

    public function base()
    {
        // router 
        $this->baseBase = $this->separatorForDir(
            DRT.DS.$this->base
        );
        $this->baseSubdomain = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain
        );
        $this->baseLayout     = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'layouts'
        );
        $this->baseController = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'controllers'
        );
        $this->baseManagement = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'managements'
        );
        $this->baseModel      = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'models'
        );
        $this->baseScript     = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'scripts'
        );
        $this->baseMedia      = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'medias'
        );
        $this->baseView       = $this->separatorForDir(
            DRT.DS.$this->base.DS.$this->subdomain.DS.'views'
        );

        $this-> baseUrl();
    }

    public function baseUrl()
    {
        // router 
        $this->baseUrlBase = $this->separatorForNamespace(
            $this->base.DS
        );
        $this->baseUrlSubdomain = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS
        );
        $this->baseUrlLayout     = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'layouts'.DS
        );
        $this->baseUrlController =  $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'controllers'.DS
        );
        $this->baseUrlManagement = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'managements'.DS
        );
        $this->baseUrlModel      = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'models'.DS
        );
        $this->baseUrlScript     = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'scripts'.DS
        );
        $this->baseUrlMedia      = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'medias'.DS
        );
        $this->baseUrlView       = $this->separatorForNamespace(
            $this->base.DS.$this->subdomain.DS.'views'.DS
        );
    }

    public function namespaces()
    {
        $this->namespaceControllers = "$this->base\\$this->subdomain\\controllers\\$this->controller\\";
        $this->namespaceModels      = "$this->base\\$this->subdomain\\models\\";
        $this->namespaceManagements = "$this->base\\$this->subdomain\\managements\\";
    }

    public function atual()
    {
        $this->namespaces();
        
        // router 
        $this->atualBase       = $this->baseBase;
        $this->atualSubdomain  = $this->baseSubdomain;
        $this->atualManagement = $this->baseManagement;
        $this->atualModel      = $this->baseMode;
        $this->atualScript     = $this->baseScript;
        $this->atualMedia      = $this->baseMedia;
        $this->atualLayout     = $this->baseLayout;
        $this->atualController = $this->separatorForDir(
            $this->baseController.DS.$this->controller
        );
        $this->atualAction     = $this->separatorForDir(
            $this->baseController.DS.$this->controller.DS.$this->action
        );
        $this->atualView       = $this->separatorForDir(
            $this->baseView.DS.$this->controller
        );

        $this->atualUrl();
    }

    public function atualUrl()
    {
        // router 
        $this->atualUrlBase       = $this->baseUrlBase;
        $this->atualUrlSubdomain  = $this->baseUrlSubdomain;
        $this->atualUrlManagement = $this->baseUrlManagement;
        $this->atualUrlModel      = $this->baseUrlModel;
        $this->atualUrlScript     = $this->baseUrlScript;
        $this->atualUrlMedia      = $this->baseUrlMedia;
        $this->atualUrlLayout     = $this->baseUrlLayout;
        $this->atualUrlController = $this->separatorForNamespace(
            $this->baseUrlController.DS.$this->controller.DS
        );
        $this->atualUrlAction     = $this->separatorForNamespace(
            $this->baseUrlController.DS.$this->controller.DS.$this->action.DS
        );
        $this->atualUrlView       = $this->separatorForNamespace(
            $this->baseUrlView.DS.$this->controller.DS
        );

        $view = $this->atualUrlView;
    }

        /**
     * Undocumented function
     *
     * @return void
     */
    public function defines()
    {
        define('__oScripts',str_replace(
            array('/','//','\\','\\\\'),
            '/',
            $this->base.DS.$this->subdomain.DS.'scripts'.DS)
        );
        define('__oBase',str_replace(array('/','//','\\','\\\\'),
            '/',
            DRT.DS)
        );
        define('__oBaseScripts',str_replace(array('/','//','\\','\\\\'),
                '/',
                __oBase.__oScripts)
        );
        define('__oLocal',PROTOCOL.HH);
        define('__oLocalScripts', __oLocal.DS.__oScripts);
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
}