<?php
/**
 * MVC
 *
 * Suporte à requisições WEB com MVC.
 * carregamento de classes semelhantes como propriedades.
 * @version 1.00000.00.00000
 * @copyright De Souza Informática - 2016
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
 *
 */

namespace vendor\nuclear\system\control;

use vendor\nuclear\system\view\view;
use vendor\nuclear\system\model\entity;
use vendor\nuclear\system\model\table;
use vendor\nuclear\app\orbe;
use vendor\nuclear\configs\cfg;
use vendor\nuclear\system\model\orm;
	
/**
 * Carrega registro de routes conforme a string de requesição
 * @copyright De Souza Informática
 * @author Douglas Gonçalves de Souza
 * @version 16/09/2015 1.006.0000
 */
abstract class controller
{

    public  $variables = null;
    public  $view      = null;
    public  $template  = null;
    private $models    = null;
    private $notFound  = null;
    public  $orm       = null;
    static  $request   = null;
    
    /**
     * Evento construtor da classe
     */
    public function __construct()
    {
        $this->__start();
    }

    /**
     * Função que inicialização
     *
     * @return void
     */
    public function __start()
    {
        if(isset(self::$request)){
            $view = str_replace(
                array('/','//','\\','\\\\'),
                '/',
                self::$request->view);
            $this->view = new view($view, self::$request->localView);
            
            // Altera valores de propriedadess
            if(isset($this->view))
                $this->chargesPropertys();
            
            // define variaveis para o ambiente
            $this->defineVariables();

        }
    }

    /**
     * Atualizações internas e de trabalho
     */

    /**
     * Alter propertys
     *
     * @return void
     */
    private function chargesPropertys()
    {
        orm::setModel(self::$request->localModel);
        $this->addLayout(self::$request->fileLayout);
        $this->notFound(self::$request->notFound);
        $this->setTemplates(self::$request->localView);
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
        define('__oLocal',cfg::rescue('general')['BASEHOST']);
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
     * Define valor da pasta de templates na view
     *
     * @param [type] $templates
     * @return void
     */
    public function setTemplates($templates)
    {
        if(isset($templates) && !empty($templates))
            $this->view->templates = $templates;
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
        if(isset($name) || strlen($name) == 0){
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
     * Responde � requisi��o com uma view
     * @param unknown $my
     */
    final public function view($template = null, $model = null)
    {
        if(isset($template) && !empty($template))
            $this->template($template);
        $this->view->view($model);
    }

    /**
     * Responde uma requisi��o para um desenvolvimento
     * @param unknown $my
     */
    final public function development($template = null, $model = null, $layout = null)
    {
        $this->view->development($template, $model, $layout);
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
     * Expoe
     * @return type
     */
    final public function getModel()
    {
        return $this->models;
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
}