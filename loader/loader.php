<?php
/**
 * Loader
 *
 * Inclusão de arquivos com ou sem sufixo default com o
 * carregamento de classes semelhantes como propriedades.
 * @version 1.00000.00.00000
 * @copyright De Souza Informática - 2016
 * @license Este trabalho está licenciado sob uma Licença
 * Creative Commons Atribuição-NãoComercial-SemDerivações
 * 4.0 Internacional. Para ver uma cópia desta licença,
 * visite http://creativecommons.org/licenses/by-nc-nd/4.0/.
 *
 */
namespace vendor\douggs\nuclear\loader;

use vendor\douggs\nuclear\configs\cfg;

abstract class loader
{
		
	static public $root;
	static public $file;
 
	public static function start($root = null)
	{
		self::setRoot($root);
		// define a função autoloader
		return spl_autoload_register("self::cms_autoloader");
	}
 
	// Carregamento automático do arquivo php
	public static function cms_autoloader($classe = null)
	{           
		self::setFile(self::getRoot() . DS . $classe . '.php');

		if(!file_exists(self::getFile()))
			throw new \Exception('Not fount autoload class: ' . self::getFile());          

		include_once self::getFile();
		return;
 	}
                
    /**
     * Preenche variável de sessão caso seja um endereço de controller incluído
     * @param string $local
     */
	static private function set_last_controller_included($local)
	{     
        if( strpos($local, DS.'controllers' ) !== false){
            $_SESSION['last_controller_included'] = $local;
        }
    }
 
	/**
	 * Get the value of root
	 */ 
	static public function getRoot()
	{
		return self::$root;
	}

	/**
	 * Set the value of root
	 *
	 * @return  bool
	 */ 
	static public function setRoot($root)
	{
		self::$root = $root;

		return self;
	}

	/**
	 * Get the value of file
	 */ 
	public static function getFile()
	{
		return self::$file;
	}

	/**
	 * Set the value of file
	 *
	 * @return  self
	 */ 
	public static function setFile($file)
	{
		self::$file = str_replace(
			array('\\','/'),
			DS,
			$file
		);
		return self;
	}
}

?>