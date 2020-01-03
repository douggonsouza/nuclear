<?php

namespace Nuclear\alerts;

abstract class alerts{

    static $alerta = '';

    static $modelo = '<div>
                            <div class="alert %s alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true" style="float:right">&times;</button>
                                <strong>%s</strong> %s</div>
                        </div>';

    const SUCCESS = 'success';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const INFO    = 'info';

    /** 
     * Evento de constru��o da classe
     * 
     */
    public function __construct()
    {
        self::searchInSession($_SESSION);
    }

    /**
     * Busca pela mensagem de alerta na sess�o
     * 
     * @param array $session
     * 
     * @return bool
     */
    static final function searchInSession($session)
    {
        if(!isset($session['msgAlert']) || empty($session['msgAlert'])){
            return false;
        }
        self::$alerta = $session['msgAlert']['msg'];
        return true;
    }

    /**
     * Salva na sess�o a mensagem de alerta
     * 
     * @return array
     */
    static final function saveInSession()
    {
        if(!isset(self::$alerta) || empty(self::$alerta)){
            return false;
        }
        $_SESSION['msgAlert'] = [
            'msg' => self::$alerta
        ];
        return true;
    }

    /**
     * Devolve o conte�do do alerta
     * 
     * @param string $mensagem
     * @param string $type
     * 
     * @return string
     */
    static final public function set($mensagem,$type = 'success'){

        // testa o conteúdo da variável
        if(isset($mensagem) && strlen($mensagem) > 0){
            switch($type){
                case self::SUCCESS:                        
                    self::$alerta = sprintf(self::$modelo, 'alert-success', 'Success!:', $mensagem);
                    break;
                case self::ERROR:  
                    self::$alerta = sprintf(self::$modelo, 'alert-danger', 'Danger!: ', $mensagem);                      
                    break;
                case self::WARNING:
                    self::$alerta = sprintf(self::$modelo, 'alert-warning', 'Warning!: ', $mensagem);                       
                    break;                    
                case self::INFO:
                    self::$alerta = sprintf(self::$modelo, 'alert-info', 'Info!: ', $mensagem);                        
                    break;
                default: 
                    self::$alerta = sprintf(self::$modelo, 'alert-success', 'Success!: ', $mensagem);                                              
            }
        }
        return self::$alerta;
    }

    /**
     * Retorna alerta definido
     * 
     * @param string $clear
     * 
     * @return mixed
     */
    final public function get($clear = true)
    {
        $alert = self::$alerta;                  		
        if(isset(self::$alerta) && !empty(self::$alerta)){
            if($clear) self::$alerta = '';
            return $alert;
        }
        return '';
    }

    /**
     * Limpa a mensagem de alerta
     */
    final public function clear()
    {
        self::$alerta = '';
        return self;
    }

    final function exist()
    {
        return isset(self::$alerta) && !empty(self::$alerta);
    }

    /**
     * Get the value of modelo
     */ 
    public function getModelo()
    {
        return self::$modelo;
    }

    /**
     * Set the value of modelo
     *
     * @return  self
     */ 
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }
}

    