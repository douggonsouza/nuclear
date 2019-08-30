<?php
    /*
     * CARREGAR DADOS GLOBAIS DE ACESSO
     * 
     * Framework Orbe - TRR
     * author Douglas Gonçalves de Souza
     * home - version 1.0000.0000.0000 - 2019-08-15
     * 
     * Creative Commons - Atribuição-NãoComercial-CompartilhaIgual 4.0 Internacional.
     * Baseado no trabalho disponível em https://bitbucket.org/douggonsouza/anfiastro.
     * Podem estar disponíveis autorizações adicionais às concedidas no âmbito desta
     * licença em https://bitbucket.org/douggonsouza/anfiastro.
     * 
     */

    // DEFINIÇÕES DE LOCAL
    // DS - Separador de Pasta
    define('DS',DIRECTORY_SEPARATOR);
    // DRT - Pasta Root com o DS
    define('DRT',str_replace(array('\\','/'),DS,$_SERVER['DOCUMENT_ROOT']));
    // DR - Recorte sem separador de pasta no final da Pasta Root
    if((strrpos(DRT,DS)+1)==(strlen(DRT)))
        define('DR',substr((str_replace(array('\\','/'),DS,$_SERVER['DOCUMENT_ROOT'])),0,strlen((str_replace(array('\\','/'),DS,$_SERVER['DOCUMENT_ROOT'])))-1));
    else
        define('DR',str_replace(array('\\','/'),DS,$_SERVER['DOCUMENT_ROOT']));
    // VA - Local na Vendor para a Anfiastro
    define('VA',str_replace(array('\\','/'),DS,DR.DS.'vendor/douggs/nuclear'));

    // DEFINIÇÕES DE URL
    // protocol - Protocolo utilizado na requisição
    $protocol = strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0]).'://';
    // HH - URL completa
    define('HH',$_SERVER['HTTP_HOST']);
    // REQUEST
    define('REQUEST',HH.$_SERVER['REQUEST_URI']);
    // SN - URL do DNS
    define('SN',$_SERVER['SERVER_NAME']);

        
?>