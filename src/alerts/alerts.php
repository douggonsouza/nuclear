<?php

namespace Nuclear\alerts;

abstract class alerts{

    static $modelo = __DIR__.'/alert.phtml';

    const SUCCESS = 'success';
    const ERROR   = 'error';
    const WARNING = 'warning';
    const INFO    = 'info';

    /**
     * Busca pela mensagem de alerta na sessão
     * 
     * 
     * @return bool
     */
    static final function searchInSession()
    {
        if(!isset($_SESSION['msgAlert']) || empty($_SESSION['msgAlert'])){
            return false;
        }
        return $_SESSION['msgAlert']['msgs'];
    }

    /**
     * Salva na sessão a mensagem de alerta
     * 
     * @return array
     */
    public final function saveInSession(string $alert)
    {
        if(!isset($alert) || empty($alert)){
            return false;
        }

        $_SESSION['msgAlert']['msgs'][] = $alert;
        return true;
    }

    /**
     * Devolve o conteúdo do alerta
     * 
     * @param string $mensagem
     * @param string $type
     * 
     * @return string
     */
    static final public function set($mensagem,$type = 'success')
    {
        // colhe o modelo
        if(file_exists(self::getModelo()))
            $modelo = file_get_contents(self::getModelo());

        if(!isset($modelo))
            return self;
        
        // testa o conteúdo da variável
        if(isset($mensagem) && strlen($mensagem) > 0){
            switch($type){
                case self::SUCCESS:                        
                    $alert = sprintf($modelo, 'success', 'Sucesso!: '.$mensagem);
                    break;
                case self::ERROR:  
                    $alert = sprintf($modelo, 'danger', 'Perigo!: '.$mensagem);                      
                    break;
                case self::WARNING:
                    $alert = sprintf($modelo, 'warning', 'Cuidado!: '.$mensagem);                       
                    break;                    
                case self::INFO:
                    $alert = sprintf($modelo, 'info', 'Informação!: '.$mensagem);                        
                    break;
                default: 
                    $alert = sprintf($modelo, 'success', 'Sucesso!: '.$mensagem);                                              
            }
        }

        self::saveInSession($alert);

        return self;
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
        $alerts = self::searchInSession();
                        
        if(!isset($alerts) || empty($alerts))
            self::setExists(false);

        if($clear) $_SESSION['msgAlert']['msgs'] = [];
        return implode("\n ",$alerts);
    }

    /**
     * Limpa a mensagem de alerta
     */
    final public function clear()
    {
        self::$alerta = [];
        return self;
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
        self::$modelo = $modelo;

        return self;
    }

    /**
     * Get the value of exists
     */ 
    final public function exist()
    {
        if(isset($_SESSION['msgAlert']) && !empty($_SESSION['msgAlert'])){
            if(!empty($_SESSION['msgAlert']['msgs']))
                return true;
            return false;
        }

        return false;
    }
}

    