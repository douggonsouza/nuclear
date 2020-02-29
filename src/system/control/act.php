<?php

namespace Nuclear\system\control;

use Nuclear\system\view\view;
use Nuclear\system\model\entity;
use Nuclear\system\model\table;
use Nuclear\app\orbe;
use Nuclear\configs\cfg;
use Nuclear\system\model\orm;
use Nuclear\alerts\alerts;
    
/**
 * Carrega registro de routes conforme a string de requesiÃ§Ã£o
 * @copyright De Souza InformÃ¡tica
 * @author Douglas GonÃ§alves de Souza
 * @version 16/09/2015 1.006.0000
 */
abstract class act
{

    public  $variables = null;
    public  $template  = null;
    private $notFound  = null;
    public  $orm       = null;
    static  $request   = null;
    static  $view      = null;

    /**
     * Undocumented function
     *
     * @return void
     */
    public function defineVariables()
    {
        define('__oScripts',str_replace(
            array('/','//','\\','\\\\'),
            '/',
            self::$request->script)
        );
        define('__oBase',str_replace(array('/','//','\\','\\\\'),
            '/',
            DRT.DS)
        );
        define('__oBaseScripts',str_replace(array('/','//','\\','\\\\'),
                '/',
                DRT.DS.self::$request->script)
        );
        define('__oLocal',PROTOCOL.HH);
        define('__oLocalScripts', __oLocal.DS.__oScripts);
    }

    /**
     * Adiciona um objeto request Ã  controller
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
     * AtualizaÃ§Ãµes em tempo de execuÃ§Ã£o
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
     * ToDo: receber somente o nome do arquivo e sua extensÃ£o
     * deixar para a funÃ§Ã£o pegar o local correto do request
     *
     * @param string $name
     * @return void
     */
    public static function addView($baseView, $view)
    {
        self::$view = new view($baseView, $view);
    }

    /**
     * Carrega o layout corrente
     * ToDo: receber somente o nome do arquivo e sua extensÃ£o
     * deixar para a funÃ§Ã£o pegar o local correto do request
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
            self::$view->layout($layout);
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
            self::$view->template($name);
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
        self::$view->view($model);
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
            alerts::saveInSession();
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
        return self::$view->getLayouts();
    }
    
    /**
     * Recebe pasta de layout corrente
     *
     * @return  self
     */ 
    final public function setLayouts($layouts)
    {
        self::$view->setLayouts($layouts);
    }

    /**
     * Get the value of layout
     */ 
    final public function getLayout()
    {
        return self::$view->getLayout();
    }
    
    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    final public function setLayout($layout)
    {
        self::$view->setLayout($layout);
    }

    /**
     * Get the value of templates
     */ 
    final public function getTemplates()
    {
        return self::$view->getTemplates();
    }

    /**
     * Define valor da pasta de templates na view
     *
     * @param string $templates
     * @return void
     */
    final public function setTemplates($templates)
    {
            self::$view->templates = $templates;
    }

    /**
     * Get the value of template
     */ 
    final public function getTemplate()
    {
        return self::$view->getTemplate();
    }
    
    /**
     * Set the value of template
     *
     * @return  self
     */ 
    final public function setTemplate($template)
    {
        self::$view->setTemplate($template);
    }

    /**
     * Get the value of view
     */ 
    final public function getView()
    {
        return self::$view->getView();
    }
    
    /**
     * Set the value of view
     *
     * @return  self
     */ 
    final public function setView($template = null, $model = null)
    {
        self::$view($template, $model);
    }
    
    /**
     * Responde requisiï¿½ï¿½o de json
     * @param unknown $my
     */
    final public function json($model)
    {
        self::$view->json($model);
    }
    
    /**
     * Responde a requisiÃ§Ã£o de html
     * @param type $model
     */
    final public function html($html)
    {
        self::$view->html($html);
    }

    /**
     * FunÃ§Ãµes intrinsicas e para override
     */    
    /**
     * Captura chamadas a funÃ§Ãµes inexistentes
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
     * FunÃ§Ã£o a ser executada no contexto da action
     */
    abstract public function main(...$param);

}