<?php
    /*
     * CARREGAR DADOS GLOBAIS DE ACESSO
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
    define('PROTOCOL', strtolower(explode('/',$_SERVER['SERVER_PROTOCOL'])[0]).'://');
    // HH - URL completa
    define('HH',$_SERVER['HTTP_HOST']);
    // REQUEST
    define('REQUEST',HH.$_SERVER['REQUEST_URI']);
    // SN - URL do DNS
    define('SN',$_SERVER['SERVER_NAME']);

    // autoloader
    include VA.DS.'autoloader.php';

    // realiza o carregamento automático do módulo
    $loading = new vendor\douggs\nuclear\autoloader();

    $loading->add(VA.DS.'configs/cfg.php')
        ->add(VA.DS.'app/propertys.php')
        ->add(VA.DS.'app/orbe.php')
        ->add(VA.DS.'events/dir_xml.php')
        ->add(VA.DS.'events/assync.php')
        ->add(VA.DS.'events/events.php')
        ->add(VA.DS.'system/mask_action/i_mask_action.php')
        ->add(VA.DS.'system/mask_action/mask_action.php')
        ->add(VA.DS.'system/model/a_entity.php')
        ->add(VA.DS.'system/model/entity.php')
        ->add(VA.DS.'system/model/table.php')
        ->add(VA.DS.'system/model/actions/Action.php')
        ->add(VA.DS.'system/model/actions/Call.php')
        ->add(VA.DS.'system/model/actions/Delete.php')
        ->add(VA.DS.'system/model/actions/Insert.php')
        ->add(VA.DS.'system/model/actions/Query.php')
        ->add(VA.DS.'system/model/actions/Select.php')
        ->add(VA.DS.'system/model/actions/Update.php')
        ->add(VA.DS.'system/model/orm.php')
        ->add(VA.DS.'system/view/mimes.php')
        ->add(VA.DS.'system/view/display.php')
        ->add(VA.DS.'system/view/view.php')
        ->add(VA.DS.'system/request/request.php')
        ->add(VA.DS.'system/control/act.php')
        ->add(VA.DS.'system/routing.php');
    $loading->loadList();

    $loading->loader(DR.DS.'root','autoloader.php');

?>