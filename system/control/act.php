<?php

namespace vendor\douggs\nuclear\system\control;

use vendor\douggs\nuclear\system\view\view;
use vendor\douggs\nuclear\system\model\entity;
use vendor\douggs\nuclear\system\model\table;
use vendor\douggs\nuclear\app\orbe;
use vendor\douggs\nuclear\configs\cfg;
use vendor\douggs\nuclear\system\model\orm;
	
/**
 * Carrega registro de routes conforme a string de requesição
 * @copyright De Souza Informática
 * @author Douglas Gonçalves de Souza
 * @version 16/09/2015 1.006.0000
 */
abstract class act
{

    public  $variables = null;
    public  $view      = null;
    public  $template  = null;
    private $notFound  = null;
    public  $orm       = null;
    static  $request   = null;
    
    /**
     * Evento construtor da classe
     */
    public function __construct()
    {
        // Função de partida
        $this->__start();
    }

    /**
     * Função que inicialização
     *
     * @return void
     */
    public function __start()
    {        
        $view = str_replace(
            array('/','//','\\','\\\\'),
            '/',
            self::$request->view);
        $this->view = new view($view, self::$request->localView);
        // Altera propriedades
        orm::setModel(self::$request->localModel);
        $this->setLayouts(self::$request->layout);
        $this->setLayout(cfg::rescue('root')['default_layout']);
        $this->notFound(self::$request->notFound);
        $this->setTemplates(self::$request->localView);
        // define variaveis para o ambiente
        $this->defineVariables();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    private function defineVariables()
    {
        $__oScripts = str_replace(
            array('/','//','\\','\\\\'),
            '/',
            self::$request->script);
        define('__oScripts',$__oScripts);
        $__oBase = str_replace(array('/','//','\\','\\\\'),
            '/',
            DRT.DS.cfg::rescue('root')['base'].DS.self::$request->subdomain);
        define('__oBase',$__oBase);
        $__oBaseScripts = str_replace(array('/','//','\\','\\\\'),
                '/',
                DS.self::$request->subdomain.DS.self::$request->script);
        define('__oBaseScripts',$__oBaseScripts);
        define('__oLocal',PROTOCOL.HH);
        $this->view->variables([
            '__oScripts'     => $__oScripts,
            '__oLocal'       => __oLocal,
            '__oBase'        => $__oBase,
            '__oBaseScripts' => $__oBaseScripts
        ]);
    }

    /**
     * Adiciona um objeto request à controller
     *
     * @param object $request
     * @return void
     */
    static public function addRequest($request = null)
    {
        if(isset($request))
            self::$request = $request;
    }

    /**
     * Atualizações em tempo de execução
     */
    /**
     * Atualiza propriedade url de Not Found
     * @param unknown $notFound
     */
    final function notFound($notFound = null)
    {
        $this->notFound = $notFound;
    }

    /**
     * Carrega o layout corrente
     * ToDo: receber somente o nome do arquivo e sua extensão
     * deixar para a função pegar o local correto do request
     *
     * @param string $name
     * @return void
     */
    final public function addLayout($name)
    {
        if(isset($name) || !empty($name)){
            $layout = str_replace(
                array('/','//','\\','\\\\'),
                '/',
                $name);
            $this->view->layout($layout);
            return;
        }
        throw new \Exception('Value is argument Local is null.');
    }

    /**
     * Define o template
     * @param string $local
     */
    final public function template($name = null)
    {
        if(isset($name) && strlen($name) > 0){
            $this->view->template($name);
            $this->template = $name;
            return;
        }
        throw new \Exception('Not found template.');
    }

    /**
     * Responde a requisição com uma view
     * @param unknown $my
     */
    final public function view($template = null, $model = null)
    {
        if(isset($template) && !empty($template))
            $this->setTemplate($template);
        $this->view->view($model);
    }

    /**
     * Exppoe entidade
     * @return entity
     */
    final public function entity()
    {
        return new entity();
    }

    /**
     * Expoe entidade table
     * @return system\control\table
     */
    final public function table($table,$dados = null)
    {
        return new table($table, $dados);
    }
    
    /**
     * Undocumented function
     *
     * @param [type] $local
     * @return void
     */
    final public function redirect($local)
    {
        if(isset($local) && strlen($local) > 0){
            header("location: ".$local);
            die();
        }
        return false;
    }

    /**
     * Expoe classe model
     * @return type
     */
    final public function model($name)
    {
        if(isset($name) && strlen($name) > 0){
            return orm::model($name);
        }
        throw new \Exception('Not found name for orm.');
    }

    /**
     * Expoe pasta de layout corrente
     * 
     * Get the value of layouts
     */ 
    public function getLayouts()
    {
        return $this->view->getLayouts();
    }
    
    /**
     * Recebe pasta de layout corrente
     *
     * @return  self
     */ 
    final public function setLayouts($layouts)
    {
        $this->view->setLayouts($layouts);
    }

    /**
     * Get the value of layout
     */ 
    final public function getLayout()
    {
        return $this->view->getLayout();
    }
    
    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    final public function setLayout($layout)
    {
        $this->view->setLayout($layout);
    }

    /**
     * Get the value of templates
     */ 
    final public function getTemplates()
    {
        return $this->view->getTemplates();
    }

    /**
     * Define valor da pasta de templates na view
     *
     * @param string $templates
     * @return void
     */
    final public function setTemplates($templates)
    {
            $this->view->templates = $templates;
    }

    /**
     * Get the value of template
     */ 
    final public function getTemplate()
    {
        return $this->view->getTemplate();
    }
    
    /**
     * Set the value of template
     *
     * @return  self
     */ 
    final public function setTemplate($template)
    {
        $this->view->setTemplate($template);
    }

    /**
     * Get the value of view
     */ 
    final public function getView()
    {
        return $this->view->getView();
    }
    
    /**
     * Set the value of view
     *
     * @return  self
     */ 
    final public function setView($template = null, $model = null)
    {
        $this->view($template, $model);
    }
    
    /**
     * Responde requisi��o de json
     * @param unknown $my
     */
    final public function json($model)
    {
        $this->view->json($model);
    }
    
    /**
     * Responde a requisição de html
     * @param type $model
     */
    final public function html($html)
    {
        $this->view->html($html);
    }

    /**
     * Funções intrinsicas e para override
     */    
    /**
     * Captura chamadas a funções inexistentes
     * @param unknown $valor1
     * @param unknown $valor2
     */
    public function __call( $name, $arguments)
    {
    	if(isset($this->notFound)){
    		header("location:".$this->notFound);
    	}
    	else{
    		header("location:". $this->request->notFound);
    	}
    }

    /**
     * Para ser disparado antes
     *
     * @return void
     */
    public function __before()
    {

    }

    /**
     * Para ser disparado depois
     *
     * @return void
     */
    public function __after()
    {

    }

    /**
     * Função a ser executada no contexto da action
     */
    abstract public function main(...$param);

}