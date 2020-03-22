<?php

namespace Nuclear\system\control;

use Nuclear\system\view\views;
use Nuclear\system\model\orm;
use Nuclear\alerts\alerts;
use Nuclear\system\view\viewInterface;
    
/**
 * Carrega registro de routes conforme a string de requesição
 */
abstract class action implements viewInterface
{ 
    private $notFound = null; // Endereço da página de Not Found do sistema
    static  $router   = null; // Objeto com as rotas definidas em tempo de execução
    static  $view     = null; // Objeto de visualização

    /**
     * Adiciona um objeto router à controller
     *
     * @param object $request
     * @return void
     */
    static public function addRouter($router = null)
    {
        if(isset($router))
            self::$router = $router;
    }

    /**
     * Atualização em tempo de execução
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
    public static function addView($baseLayout, $layout, $baseView, $atualView, $view)
    {
        self::$view = new views($baseLayout, $layout, $baseView, $atualView, $view);
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
            self::$view->layout(str_replace(['/','//','\\','\\\\'], '/', $name));
            return;
        }
        throw new \Exception('Layout not found.');
    }

    /**
     * Define o block
     * @param string $local
     */
    final public function block($block = null)
    {
        return self::$view->block($block);
    }

    /**
     * Responde a requisição com uma view
     * @param unknown $my
     */
    final public function view($block = null, $params = null)
    {
        self::$view->view($block, $params);
    }

    /**
     * Requisita o Block na raiz da VIEW
     * @param string $name
     * @return type
     */
    public function partial($block, $params = null)
    {
        return self::$view->partial($block, $params);
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
     * Acrescenta array à params da página
     * @param array $params
     */
    final public function params($params = null)
    {
        return self::$view->params($params);
    }

    /**
     * Estancia model 
     * @return type
     */
    final public function model($name)
    {
        if(isset($name) && strlen($name) > 0){
            return orm::model($name);
        }
        return;
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
     * Responde requisição de json
     * @param unknown $my
     */
    final public function json($model)
    {
        return self::$view->json($model);
    }
    
    /**
     * Responde a requisiÃ§Ã£o de html
     * @param type $model
     */
    final public function html($html)
    {
        return self::$view->html($html);
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
        throw new \Exception('Function not found.');
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
     * Funções a ser executada no contexto da action
     */
    abstract public function main(...$param);

}