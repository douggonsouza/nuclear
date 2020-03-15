<?php

	// inclui configurações locais
	include_once dirname(__FILE__) .'/../configs/cfg.php';

	// arquivos usados
	use Nuclear\configs\cfg;
	// use Nuclear\loader\loader;
	use Nuclear\system\model\conn;
    use Nuclear\events\events;
	use Nuclear\system\routing;
	use Nuclear\system\Response;
	use Nuclear\app\orbe;
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
    // loader::start($_SERVER['DOCUMENT_ROOT']);
	/**
	 * Events
	 * Inicia sistema de eventos e carrega observers
	 */
	// guarda objeto no app
	orbe::add('events', new events(dirname(__FILE__).'/../events'.DS.'observers'.DS));
	/*
	 Connection
	 Inicia connexão com o banco de dados
	 */
	// guarda objeto no app
	orbe::add('conn', conn::connection());
	/*
	 Manager
	 Inicia módulo para a requisição de página
	 */
	// guarda objeto no app
	// orbe::add('system', new routing());
	orbe::add('system', new Response());
	orbe::rescue('system')->routes(REQUEST);	

?>
