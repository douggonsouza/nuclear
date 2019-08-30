<?php

    /*
     * Framework Orbe - TRR - version 1.0000.0000.0000 - 2016-08-05
     * author Douglas Gonçalves de Souza
     * 
     * 
     * Licença Creative Commons
     * Orbe Framework de Douglas Gonçalves de Souza está licenciado com uma Licença
     * Creative Commons - Atribuição-NãoComercial-CompartilhaIgual 4.0 Internacional.
     * Baseado no trabalho disponível em https://bitbucket.org/douggonsouza/terra_nova.
     * Podem estar disponíveis autorizações adicionais às concedidas no âmbito desta
     * licença em https://bitbucket.org/douggonsouza/terra_nova.
     */

	// inclui configurações locais
	include_once dirname(__FILE__) .'/../loader/loader.php';
	include_once dirname(__FILE__) .'/../configs/cfg.php';
	include_once dirname(__FILE__) .'/../configs/geral.cfg';
	// arquivos usados
	use vendor\douggs\nuclear\configs\cfg;
    use vendor\douggs\nuclear\loader\loader;
    use vendor\douggs\nuclear\events\events;
    use vendor\douggs\nuclear\system\routing;
	use vendor\douggs\nuclear\app\orbe;
    // Seta pasta de salvamento da sessão
    session_save_path(dirname(__FILE__) .'/../session');
    // inicia sessão da página
	session_start();
	/**
	 * CONFIGS
	 * 
	 * carrega arvore de configurações
	 * Exemplo Array Sem Index
	 * $cfg = [
    	 *      'root' => [
   	 *          'default' => 'main']
   	 * ]
	 * Exemplo Array Com Index
	 * $root = [
   	 * 		'default' => 'main']
   	 * ]
	 */
	// guarda objeto no app
	cfg::add($cfg);
    /*
    Loader
    Instrancia e registra autoloader
    */
    // inclui classe autoloader 
    loader::start($_SERVER['DOCUMENT_ROOT']);
	/**
	 * Events
	 * Inicia sistema de eventos e carrega observers
	 */
	// guarda objeto no app
	orbe::add('events', new events(dirname(__FILE__).'/../events'.DS.'observers'.DS));
	/*
	 Manager
	 Inicia módulo para a requisição de página
	 */
	// guarda objeto no app
	orbe::add('system', new routing());
	orbe::rescue('system')->routes(REQUEST);	

?>

