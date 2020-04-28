<?php

namespace Nuclear\system\view;

interface viewInterface
{    
    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    public function setLayout($layout);

    /**
     * Seta propriedade Block
     * @param type $local
     * @return boolean
     * @deprecated
     */
    public function block($block = null);
    
    /**
     * Set the value of view
     *
     * @return  self
     */ 
    public function view($template = null, $model = null);

    /**
     * Responde requisição de json
     * @param unknown $my
     */
    public function json($params);

    /**
     * Responde a requisição de html
     * @param type $model
     */
    public function html($html);

    /**
     * Requisita o Block na raiz da VIEW
     * @param string $name
     * @return type
     */
    public function partial($block, $model = null);   
}