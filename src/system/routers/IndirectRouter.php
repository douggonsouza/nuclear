<?php

namespace Nuclear\system\routers;

use Nuclear\system\routers\Router;
use Nuclear\system\control\action;
use Nuclear\system\request\request;
use Nuclear\system\model\orm;
use Nuclear\system\view\view;
use Nuclear\system\routers\routerInterface;
use Nuclear\system\nuclear;
use Nuclear\loader\loader;
use Nuclear\configs\cfg;
use Nuclear\app\orbe;
use Nuclear\system\Calls;
use Nuclear\system\view\display;

   
class IndirectRouter extends nuclear implements routerInterface
{
    protected $url           = null;    // Url recebida
    protected $router        = null;    // Objeto de rotas
    protected $query_string  = null;    // String com os parametros GET
    protected $request       = null;    // Resultado da Url tratada
    protected $defaultBase      = null; // Nome da base default
    protected $defaultSubdomain = null; // Nome do subdomain default
    protected $calls            = null; // Objeto de chamadas
    protected $controller       = null; // Ojbeto instanciado da controller de requisição

    /**
      * Define a rota para a controller, action e variaveis
      *
      * @param string $url
      * @return array
      */
      public function render($url)
      {
        $this->calls  = new Calls();
        $this->router = new Router($url, true);

        // Trata a Url
        if(!$this->clearUrl($url))
            throw new \Exception('Do not possible clear Url.');

        // carrega rotas
        $this->routings();

        // Altera propriedades
        orm::setModel($this->router->namespaceModels);
        
        if(!$this->configAct())
            throw new \Exception('Do not possible change Act.');
        
        // executa controller
        $this->controller = $this->calls->init(
            $this->router->atualAction,
            $this->router->namespaceControllers.$this->router->action,
            $this->router->atualArguments
        );
          
        return true;
    }

    private function configAct()
    {
        // carrega as rotas e guarda na controller
        action::addRouter($this->router);

        // carrega view
        action::addView(
            $this->router->baseLayout,
            $this->router->layout,
            $this->router->baseView,
            $this->router->atualView,
            $this->router->action
        );

        return true;
    }

    /**
     * Extrai o subdominio
     *
     * @param string $base
     * @return bool
     */
    public function subdomain()
    {
        $arrUrl = [];
        if(isset($this->url) && is_string($this->url))
            $arrUrl = explode('.',$this->url);
        $subdomain = strtolower($arrUrl[0]);
        if(isset($arrUrl[0])){
            if(is_dir(DRT.DS.'root'.DS.$subdomain)){
                $this->router->subdomain = $subdomain;
            }
            return true;
        }
        return false;
    }

    /**
     * Undocumented function
     *
     * @param string $url
     * @return string
     */
    public function clearUrl($url)
    {
        if(!isset($url))
        return false;
    
        $this->url = $url; 
        
        // extrai subdomain
        $this->subdomain();
        if(!isset($this->router->subdomain) || (!isset($this->url) || strlen($this->url) == 0))
            throw new \Exception('Not found routes.');

        if(isset($this->url) && strlen($this->url) > 0){
            $this->request = $this->clearQueryString($this->url);
            $this->request = $this->clearHost($this->request);
        }
        $this->router->request = $this->request;

        return true;
    }

    public function clearQueryString($request)
    {
        if(!isset($request) || strlen($request) == 0)
            return null;
        if(!isset($_SERVER['QUERY_STRING']) || strlen($_SERVER['QUERY_STRING']) == 0)
            return $request;
        $this->query_string = $_SERVER['QUERY_STRING'];
        return str_replace('?'.$this->query_string,'',$this->reque);
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
    
    /**
     * Indirect request
     *
     * @return void
     */
    private function routings()
    {
        $defaultNotFound = cfg::rescue('root')['default_not_found'];
        
        // base
        $this->router->base();

        // define rotas
        $this->defineRoutes($this->request);

        // base atual
        $this->router->atual();

        $this->router->defines();

        $this->router->isError = false;

        return true;
    }

    /**
     * Define a rotas pela urlUndocumented function
     *
     * @return void
     */
    public function defineRoutes($request)
    {
        $arrCount = 0;

        $wall = explode(DS,$this->request);
        $clearWalls = array_filter($wall);
        if(empty($clearWalls) || $request == '/'){
            $this->router->isController = true;
            return true;
        }
        foreach($clearWalls as $value){
            switch($arrCount){
                case 0:
                    $this->router->controller = $value;
                    $this->router->isController = true;
                break;
                case 1;
                    $this->router->action = $value;
                break;
                default:
                    $this->router->atualArguments[] = $value;
                break;
            }
            $arrCount++;
        }
        return true;
    }
}