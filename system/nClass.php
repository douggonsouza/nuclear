<?php

class nClass extends \stdClass
{
    /**
     * Captura chamadas a funções inexistentes
     * @param unknown $valor1
     * @param unknown $valor2
     */
    public function __call( $name, $arguments)
    {
        throw new \Exception('Function not found.');
    }
}


?>