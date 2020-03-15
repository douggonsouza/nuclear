<?php

namespace Nuclear\system\model;

use Nuclear\system\model\executes;
use Nuclear\system\model\resources;
use Nuclear\system\model\resourceInterface;
use Nuclear\system\model\entitysInterface;

class entitys extends executes implements resourceInterface, entitysInterface
{  
    public    $key;
    public    $new = true;
    protected $resource;
    protected $columns = [];

    public function search(string $sql)
    {
        $where = [];

        if(!isset($sql) || empty($sql)){
            return false;
        }

        $this->setResource(new resources(
            $sql,
            $this->new
        ));

        return $this;
    }

    /**
     * Salva o modelo
     *
     * @return void
     */
    final public function save()
    {
        if(!isset($this->resource))
            return null;

        // colher os dados (data)
        $data = $this->resource->getData();

        if(!$this->is_new())
            return $this->updateResource($data, $this->table);

        return $this->insertResource($data, $this->table);
    }

    /**
     * Deleta logicamente o modelo
     * 
     * @return void
     */
    final public function delete()
    {
        return $this->deleteResource($data, $this->table);
    }

    /**
     * Valida se é uma model nova e vazia
     * 
     */
    public function is_new(){
        return $this->new;
    }

    /**
     * Resgata valor existente em data
     *
     * @param  string $field
     * @return void
     */
    final public function getValue($field)
    {
        if(!isset($field) || !isset($this->resource))
            return null;

        return $this->getResource()->getValue($field);
    }

    /**
     * Resgata valor existente em data
     *
     * @param  string $field
     * @return void
     */
    final public function setValue(string $field, $value)
    {
        if(!isset($field) || !isset($value))
            return null;

        $this->getResource()->setValue($field, $value);

        return $this;
    }

    /**
     * Get the value of resource
     */ 
    public function getResource()
    {
        if(!isset($this->resource) && isset($this->table)){
            $this->resource = new resources();
            $this->resource->matchInfoFields( $this->table);
        }
        
        return $this->resource;
    }

    /**
     * Set the value of resource
     *
     * @return  self
     */ 
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Colhe o valor para key
     */ 
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Define o valor para key
     *
     * @param string $key
     *
     * @return  self
     */ 
    public function setKey(string $key)
    {
        if(isset($key) && !empty($key))
            $this->key = $key;

        return $this;
    }
}