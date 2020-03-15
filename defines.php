<?php
    // autoload composer
    require_once getcwd()."/vendor/autoload.php";
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
    define('PROTOCOL', strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0]).'://');
    // HH - URL completa
    define('HH',$_SERVER['HTTP_HOST']);
    // URL
    define('BASE_URL',PROTOCOL.HH);
    // REQUEST
    define('REQUEST',HH.$_SERVER['REQUEST_URI']);
    // SN - URL do DNS
    define('SN',$_SERVER['SERVER_NAME']);