<?php

namespace Nuclear\system\routers;

interface routerInterface
{
    /**
     * Regra de negócio para a renderização da rota
     *
     * @param string $url - url da requisição
     * 
     * @return bool
     */
    public function render($url);
}