<?php

namespace Nuclear\system\view;

use Nuclear\system\view\display;
use Nuclear\app\orbe;
use Nuclear\configs\cfg;

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
    
    public function __construct($pathView = null, $view = null)
    {
        $this->setView($pathView);
        $this->setTemplate($view);
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
	 * Requisita carregamento do template com endereço completo
	 * @param unknown $my
	 */
    final public function view($model = null)
    {
        if(isset($this->layout) && strlen($this->layout) > 0){
            $this->variables($model);
            parent::body($this->layouts, $this->variables);
        }
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
            $this->layout = $local;
            return true;               
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
	 * Requisita o carregamento do template
	 * @param unknown $my
	 */
    final public function content($name = null, $model = null)
    {
        $this->variables($model);
        if(isset($name) && !empty($name))
            $this->setTemplate($name);                    
        parent::body($this->templates.DS.$this->template, $this->variables);
        return true;
	}
    
    /**
     * Responde a requisição com um array do tipo json
     * @param array $variables
     */
    final public function json($params)
    {
        if(!isset($params) || empty($params))
            throw new \Exception("Parameters JSON not found");
        header('Content-Type: application/json');
        exit(json_encode($params));
    }
    
    /**
     * Resposde a requisição com html
     * @param string $html
     */
    final public function html($html)
    {
        if(!isset($html) || empty($html))
            throw new \Exception("HTML responce not found");
        header('Content-Type: application/json');
        exit($html);
    }
    
    /**
     * Requisita o template na raiz da VIEW
     * @param string $name
     * @return type
     */
    final public function partial($name, $model = null)
    {
        $partial = str_replace(
            array('/','//','\\','\\\\'),
            '/',
            $this->localView.'/../'.DS.$name);
        parent::body($partial,$model);
        return;
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
