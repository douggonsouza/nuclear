<?php

/**
 * NUCLEAR - VIEW
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

interface contract_view
{

    /**
     * Expoe pasta de layout corrente
     * 
     * Get the value of layouts
     */ 
    public function getLayouts();
    
    /**
     * Recebe pasta de layout corrente
     *
     * @return  self
     */ 
    public function setLayouts($layouts);

    /**
     * Get the value of layout
     */ 
    public function getLayout();
    
    /**
     * Set the value of layout
     *
     * @return  self
     */ 
    public function setLayout($layout);

    /**
     * Get the value of templates
     */ 
    public function getTemplates();

    /**
     * Define valor da pasta de templates na view
     *
     * @param string $templates
     * @return void
     */
    public function setTemplates($templates = null);

    /**
     * Get the value of template
     */ 
    public function getTemplate();
    
    /**
     * Set the value of template
     *
     * @return  self
     */ 
    public function setTemplate($template);

    /**
     * Get the value of view
     */ 
    public function getView();
    
    /**
     * Set the value of view
     *
     * @return  self
     */ 
    public function setView($template = null, $model = null);

    /**
     * Responde uma requisi��o para um desenvolvimento
     * @param unknown $my
     */
    public function development($template = null, $model = null, $layout = null);

    /**
     * Responde requisi��o de json
     * @param unknown $my
     */
    public function json($model);

    /**
     * Responde a requisição de html
     * @param type $model
     */
    public function html($html);
    
}




?>