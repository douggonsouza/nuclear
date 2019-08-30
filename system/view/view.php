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

namespace vendor\nuclear\system\view;

use vendor\nuclear\system\view\display;
use vendor\nuclear\app\orbe;
use vendor\nuclear\configs\cfg;

class view extends display
{

    //----- PROPERTS -----

    private $my        = null;
    private $variables = array();
    public  $helpers   = null;
    public  $layouts   = null;
    public  $layout    = null;
    public  $view      = null;
    public  $templates = null;
    public  $template  = null;
    public  $localView = null;
    
    public function __construct($pathView = null, $localView = null)
    {
        $this->setView($pathView);
        $this->setLocalView($localView);
    }

    /**
     * Define a pasta de localização da LocalView
     *
     * @param string $view
     * @return void
     */
    final public function setLocalView($view)
    {
        if(isset($view))
            $this->localView = $view;
    }

    /**
     * Acrescenta array à variables da página
     * @param array $variables
     */
    final public function variables($variables = null)
    {
        if(!empty($variables)){
            foreach($variables as $index => $value)
                $this->variables[$index] = $value;
            return true;
        }
        return false;
    }

	/**
	 * Prepara resposta tipo view
	 * @param unknown $my
	 */
    final public function view($model = null)
    {
        if(isset($this->layout) && strlen($this->layout) > 0){
            $this->variables($model);
            parent::body($this->layout, $this->variables);
        }
    }

    /**
     * Prepara resposta tipo view
     * @param unknown $my
     */
    final public function video($template, $model = null, $layout = null)
    {
        if(isset($template) && strlen($template) > 0){
            $this->variables($model);
            $this->setTemplate($template);
            $this->layout($layout);                                     
            parent::body($this->layout, $this->variables);
        }
    }

    /**
     * Resposta para �rea tipo development
     * @param unknown $variables
     * @param unknown $layout
     * @param unknown $template
     * @throws Exception
     */
    final public function development($template, $variables = null, $layout = null)
    {

        try{
            $this->video($template, $variables, $layout);
        }
        catch(EngineException $e){
            var_dump( $e->getMessage());
        }
        echo'   <style>
            .info{
                width: 100%;
                font-family: Arial;
                font-size: 9pt;
            }
            .info tr td{
                display: block;
                margin: 0 auto;
            }
            .info div{
                background-color: #111111;
            }
            .info h5{
                color: blue;
                margin: 0;
                padding: 0;
                font-size: 10pt;
            }
            .info span{
                font-family: Arial;
                font-size: 9pt;
                color: #999999;
            }
        </style>
            <table class="info">
                <tr>
                    <td>
                        <button onclick="window.history.back();"> Voltar </button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div><h5>VARIAVEIS DE AMBIENTE</h5><span>'.$this->arrayToHTML($this->variables).'</span></div>
                        <div><h5>VARIAVEIS SESSAO</h5><span>'.$this->arrayToHTML($_ENV).'</span></div>
                        <div><h5>SESSION</h5><span>'.$this->arrayToHTML($_SESSION).'</span></div>
                        <div><h5>REQUEST</h5><span>'.$this->arrayToHTML($_REQUEST).'</span></div>
                        <div><h5>QUERYS</h5><span>'.$this->arrayToHTML($log::getQuery()).'</span></div>
                        <div><h5>LAYOUT</h5><span>'.$this->layout.'</span></div>
                        <div><h5>TEMPLATES</h5><span>'.$this->arrayToHTML($log::getTemplate()).'</span></div>
                        <div><h5>CONTENTS</h5><span>'.$this->arrayToHTML($log::getContent()).'</span></div>
                        <div><h5>BLOCKS</h5><span>'.$this->arrayToHTML($log::getBlock()).'</span></div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button onclick="window.history.back();"> Voltar </button>
                    </td>
                </tr>
            </table>';
    }
    
    /**
     * Seta a propriedade layout
     * @param string $local
     * @return boolean
     * @deprecated
     */
    final public function layout($local)
    {
        if(isset($local)){
            if(file_exists($local)){
                $this->layout = $local;
                return true;
            }                
        }
        return false;
    }
    
    /**
     * Seta propriedade template
     * @param type $local
     * @return boolean
     * @deprecated
     */
    final public function template($name = null)
    {
        if(!isset($name))
            return false;

        $this->template = $name;
        return true;
    }
    
    /**
	 * Prepara resposta tipo content
	 * @param unknown $my
	 */
    final public function content($name = null, $model = null)
    {
        if( isset($name) && !empty($name)){
            $this->variables($model);
            $this->setTemplate($name);                    
            if(isset($this->template))
                parent::body($this->templates.DS.$this->template, $this->variables);
            return true;
        }
        $this->variables($model);
        if(isset($this->template)){
            parent::body($this->templates.DS.$this->template, $this->variables);
            return true;
        }
        return false;
	}

    /*
	 * Prepara resposta tipo content
	 * @param unknown $my
	 */
    final public function block($template = null, $model = null)
    {
        $saida = '';
        $this->variables($model);
        if( isset($this->rota['action']) ){                   
            $tmp = $this->view.DS.$template;
            parent::body($tmp, $this->variables);
        }
        else{
            if(isset($this->template) && strlen($this->template) > 0){
                parent::body($this->template, $this->variables);
            }
        }
	}
    
    /**
     * Responde requisi��o de json
     * @param array $variables
     */
    final public function json($variables)
    {
        $this->variables($variables);
        if(isset($this->variables) && count($this->variables) > 0){
            header('Content-Type: application/json');
            exit( (json_encode($this->variables)) );
        }
        exit(null);
    }
    
    /**
     * Resposde a requisição com html
     * @param string $html
     */
    final public function html($html)
    {
        if(isset($html) && strlen($html) > 0){
            header('Content-Type: application/json');
            exit($html);
        }
        exit(null);
    }
    
    /**
     * Carrega parte da página
     * @param string $name
     * @return type
     */
    final public function partial($name, $model = null)
    {
        $partial = str_replace(
            array('/','//','\\','\\\\'),
            '/',
            $this->localView.'/../'.DS.$name);
        if(file_exists($partial)){
            parent::body($partial,$model);
            return;
        }
        throw new \Exception('Not found partial.');
	}
	

	/**
	 * Cria variável global a partir de params
	 * @param array $params
	 */
    private function define_var_global()
    {
		if(isset($this->variables) && is_array($this->variables) && count($this->variables) > 0){
            foreach($this->variables as $key => $vle){                       
			    $$key = $vle;
            }
            return true;    	
		}    		
        return false; 		 
    }
    
    /**
     * 
     */
    private function existExtensionTemplate($filename)
    {
        if(strpos($filename,'.phtml') === false)
            return $filename.'.phtml';
        return $filename;
    }

    public function arrayToHTML($lista){

        $sd = '';
        if(isset($lista) && count($lista) > 0){
            foreach($lista as $chv => $vle){
                $sd .= '<div>';
                if(is_array($vle))
                    $sd .= '<span>'.$chv.' : '.$this->arrayToHTML($vle).'</span>';
                elseif(is_string($vle))
                    $sd .= '<span>'.$chv.' : '.$vle.'</span>';
                $sd .= '</div>';
            }
        }
        return $sd;
	} 

    /**
     * Get the value of layouts
     */ 
    public function getLayouts()
    {
        return $this->layouts;
    }

    /**
     * Set the value of layouts
     *
     * @return  self
     */ 
    public function setLayouts($layouts)
    {
        if(isset($layouts) && !empty($layouts))
            $this->layouts = $layouts;
        return $this;
    }

    /**
     * Get the value of layout
     */ 
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    public function setLayout($layout)
    {
        if(isset($layout) && !empty($layout))
            $this->layout = $layout;
        return $this;
    }

    /**
     * Get the value of view
     */ 
    public function getView()
    {
        return $this->view;
    }

    /**
     * Define a pasta de localização da View
     *
     * @param string $view
     * @return void
     */
    final public function setView($view)
    {
        if(isset($view))
            $this->view = $view;
    }

    /**
     * Get the value of templates
     */ 
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Set the value of templates
     *
     * @return  self
     */ 
    public function setTemplates($templates)
    {
        if(isset($templates) && !empty($templates))
            $this->templates = $templates;
        return $this;
    }

    /**
     * Get the value of template
     */ 
    public function getTemplate()
    {
            return $this->template;
    }

    /**
     * Set the value of template
     *
     * @return  self
     */ 
    public function setTemplate($template)
    {
        if(isset($template) && !empty($template))
            $this->template = $this->existExtensionTemplate($template);
        return $this;
    }
}        
