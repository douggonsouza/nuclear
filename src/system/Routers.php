<?php

namespace Nuclear\system;

use Nuclear\configs\cfg;
use Nuclear\system\nuclear;
use Nuclear\system\routers\Router;
use Nuclear\system\routers\IndirectRouter;
use Nuclear\system\routers\DirectRouter;
use Nuclear\system\routers\SubdomainRouter;

class Routers extends nuclear
{

    protected $url       = '';
    protected $extensao  = '';
    protected $isDirect  = false;
    protected $defaultLayout    = null;
    protected $defaultSubdomain = null;
    protected $routers   = null;
    protected $router    = null;

    /**
      * Define as rotas
      *
      * @param string $url

      * @return array
      */
    public function routers($url)
    {   
        $this->setUrl($url);

        // verifica existencia de extensão
        $this->extension($this->url);

        // Rota direta
        if($this->directRouter())
            return $this->router;

        // Rota indireta
        if($this->indirectRouter())
            return $this->router;

        return $this->router;
    }

    public function setUrl($url)
    {
        if(isset($url))
            $this->url = $url;
    }

    private function directRouter()
    {
        //carregamento direto
        if($this->isDirect){
            $this->routers      = new DirectRouter();
            $this->routers->url = $this->url;
            $this->routers->extension     = $this->extension;
            $this->routers->defaultLayout = $this->defaultLayout;
            $this->router = $this->routers->render();
        }
    }

    private function indirectRouter()
    {
        //carregamento indireto
        if(!$this->isDirect){
            $this->routers = new IndirectRouter();
            $this->router = $this->routers->render($this->url);
        }
    }

    public function extension($request)
    {
        $match   = [];
        if(!isset($request) || strlen($request) == 0)
            return false; 
        preg_match('/\.\w+$/', $request, $match, PREG_OFFSET_CAPTURE);
        if(!empty($match)){
            $this->extensao = $match[0][0];
        }
        if(isset($this->extension))
            $this->isDirect = true;
    }

    /**
     * Carrega classe do tipo router
     *
     * @return void
     */
    public function router()
    {
        $this->router = new Router();
    }
}