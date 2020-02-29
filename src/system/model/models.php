<?php

namespace Nuclear\system\model;

use Nuclear\system\model\executes;
use Nuclear\system\model\resources;
use Nuclear\system\model\resourceInterface;

class models extends executes implements resourceInterface
{  
    public    $table;
    public    $key;
    public    $new = true;
    protected $resource;
    protected $columns = [];

    public function __construct(string $key = null)
    {
        if(isset($key))
            $this->getBy($key);
    }

    public function getBy($value, string $index)
    {
        if(!isset($value) || isset($this->table) || isset($this->key)){
            return false;
        }

        $sql = sprintf("SELECT * FROM %s WHERE %s = %s AND active = 1;",
        $this->table,
        $index,
        $value);
        $this->setResource(new resources($sql, $this->new));

        return $this;
    }

    public function search(array $fields)
    {
        $where = [];

        if(!isset($fields) || empty($fields)){
            return false;
        }
        foreach($fields as $index => $value){
            $where[] = $index.' = '.trim($value);
        }

        $sql = sprintf(
            "SELECT * FROM %s WHERE %s AND active = 1;",
            $this->table,
            implode(' AND ',$where)
        );
        $this->setResource(new resources($sql, $this->new));
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
        if(!isset($field) || !isset($value)  || !isset($this->resource))
            return null;

        $this->getResource()->setValue($field, $value);

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
     * Get the value of resource
     */ 
    public function getResource()
    {
        if(isset($this->resource))
            return $this->resource;

        return null;
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
     * Set the value of relationships
     *
     * @return  self
     */ 
    public function relationships(string $destinyTable, string $fieldLink)
    {
        if(!isset($destinyTable) || !isset($fieldLink))
            return null;

        if($this->getTable() === null || $this->getValue($fieldLink) === null)
            return null;

        $sql = sprintf(
            resourceInterface::RELATIONSHIPS_MANY_TO_ONE,
            $this->getTable(),
            $destinyTable,
            $fieldLink,
            $this->getValue($fieldLink)
            );

        $relationships = new resources($sql);

        return $relationships;
    }

    /**
     * Valida se é uma model nova e vazia
     * 
     */
    public function is_new(){
        return $this->new;
    }
}
