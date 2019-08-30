<?php

/**
 * Nuclear Framework
 * @copyright Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @license Copyright (C) 2019 Gonçalves Informática. All rights reserved.
 * @author Gonçalves Informática <douggonsouza@gmail.com>
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
*/

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