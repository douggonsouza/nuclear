<?php

namespace Nuclear;

class autoloader
{

    public $folder;
    public $file;
    public $list = [];
    
    /**
     * Evento de construção da classe
     */
    public function __construct($folder = null)
    {
        $this->setFolder($folder);
    }

    /**
     * Identifica todos os arquivos dentro da pasta de forma recursiva
     * 
     * @param string $dir
     * @param string $extension
     * 
     * @return bool
     */
    public function loader($dir, $extension = '.php', $noSearchDir = null)
    {
        // lista conteúdo da pasta
        $this->setFolder($dir);

        $lista = scandir($this->getFolder());
        
        // Vare a lista
        foreach ($lista as $index => $content){
            
            if($content == "." || $content == "..")
                continue;

            // caminho refereçe a um diretório não verificavel
            if(isset($noSearchDir) && stristr($dir.DS.$content,$noSearchDir) !== false)
                continue;

            $local = $dir.DS.$content;

            // Não é um diretório
            if (!is_dir($local)){

                if(strpos($local,$extension) != false)
                    $this->loaderFile($local);
                continue;
            }

            // chama diretório recursivamente
            $this->loader($local, $extension, $noSearchDir);
        }
        return true;
    }

    /**
     * Realiza o carregamento de um arquivo
     * 
     * @param string $local
     * 
     * @return bool
     */
    public function loaderFile($local)
    {
        $this->setFile($local);
        try{
            require_once $local;
        }
        catch(\Exception $e){
            throw new \Exception('Erro for include file: '.$local);
        }
        return true;
    }

    /**
     * Adiciona arquivo à lista de carregamento
     * 
     * @param string $local,
     * @param string $namespace
     * 
     * @return bool
     */
    public function add($local, $namespace = null)
    {
        isset($namespace) || !empty($namespace)? $this->list[$namespace] = $local: $this->list[] = $local;
        return $this;
    }

    /**
     * Executa o carregamento da lista de arquivo
     * 
     * @return object
     */
    public function loadList()
    {
        foreach($this->list as $index => $content){
            $this->loaderFile($content);
        }
        return true;
    }

    /**
     * Get the value of folder
     */ 
    public function getFolder()
    {
        return $this->folder;
    }

    /**
     * Set the value of folder
     *
     * @return  self
     */ 
    public function setFolder($folder)
    {
        if(isset($folder))
            $this->folder = $folder;

        return $this;
    }

    /**
     * Get the value of file
     */ 
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set the value of file
     *
     * @return  self
     */ 
    public function setFile($file)
    {
        if(isset($file) || !empty($file))
            $this->file = $file;

        return $this;
    }

    /**
     * Get the value of list
     */ 
    public function getList()
    {
        return $this->list;
    }

    /**
     * Set the value of list
     *
     * @return  self
     */ 
    private function setList($list)
    {
        if(isset($list) && is_array($list))
            $this->list = $list;

        return $this;
    }
}

