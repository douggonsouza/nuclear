<?php

namespace Nuclear\system\view;

use Nuclear\system\view\display;
use Nuclear\app\orbe;
use Nuclear\configs\cfg;
use Nuclear\system\view\viewInterface;

class views extends display implements viewInterface
{
    const HELPER_NAMESPACE = 'Nuclear\\system\\view\\helpers\\';
    protected $params       = array(); // Array de variáveis que serão transformadas em parâmetros de ambiente
    public $baseLayouts  = null; // Localização da pasta de layout
    public $layout       = null; // Nome do arquivo de layout
    public $baseViews    = null; // Localização da pasta das views
    public $atualView    = null; // Localização da pasta da view atual
    public $view         = null; // Nome do arquivo de view
    
    public function __construct($baseLayouts, $layout, $baseViews, $atualView, $view)
    {
        $this->baseLayouts = $baseLayouts;
        $this->layout      = $layout;
        $this->baseViews   = $baseViews;
        $this->atualView   = $atualView;
        $this->view        = $view;
    }

    /**
     * Instancia de classe helper
     *
     * @param string $class
     * @return void
     */
    final public function helper(string $class)
    {
        $class = self::HELPER_NAMESPACE.$class;
        return new $class();
    }

    /**
     * Acrescenta array à params da página
     * @param array $params
     */
    final public function params($params = null)
    {
        if(!empty($params)){
            foreach($params as $index => $value)
                $this->params[$index] = $value;
            return true;
        }
        return false;
    }

	/**
	 * Requisita carregamento do Block com endereço completo
	 * @param unknown $my
	 */
    final public function view($block = null, $model = null)
    {
        if(isset($model) && !empty($model))
            $this->params(array_merge ($this->params, $model));

        if(isset($block) && !empty($block))
            $this->setBlock($block);        

        parent::body(
            $this->existExtensionBlock($this->baseLayouts.DS.$this->layout),
            $this->params
        );
        
        return;
    }
    
    /**
	 * Requisita carregamento do Block
	 * @param unknown $my
	 */
    final public function content($block = null, $params = null)
    {
        if(isset($params) && !empty($params))
            $this->params(array_merge ($this->params, $params));

        if(isset($block) && !empty($block))
            $this->setBlock($block);        

        parent::body(
            $this->existExtensionBlock($this->atualView.DS.$this->view),
            $this->params
        );
        
        return;
	}
    
    /**
     * Seta a propriedade layout
     * @param string $local
     * @return boolean
     * @deprecated
     */
    final public function layout($layout = null)
    {
        if(isset($local))
            $this->layout = $layout;
        return $this->layout;
    }
    
    /**
     * Seta propriedade Block
     * @param type $local
     * @return boolean
     * @deprecated
     */
    final public function block($block = null)
    {
        if(isset($block))
            $this->view = $block;

        return $this->view;
    }
    
    /**
     * Responde a requisição com um array do tipo json
     * @param array $params
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
     * Requisita o Block na raiz da VIEW
     * @param string $name
     * @return type
     */
    final public function partial($block, $params = null)
    {
        if(isset($params) && !empty($params))
            $this->params(array_merge ($this->params, $params));        

        parent::body(
            $this->existExtensionBlock($this->baseViews.DS.$this->view),
            $this->params
        );

        parent::body(str_replace(array(
            '/','//','\\','\\\\'),
            '/',
            $this->baseViews.DS.$block)
        ,$model);
        return;
	}
    
    /**
     * 
     */
    private function existExtensionBlock($filename)
    {
        if(strpos($filename,'.phtml') === false)
            return $filename.'.phtml';
        return $filename;
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

    public function getParams()
    {
        return $this->params;
    }
}        