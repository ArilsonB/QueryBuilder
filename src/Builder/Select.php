<?php

  namespace MyB\QueryBuilder;

  class Select {
    private array $fields = [];
    private array $table = [];
    private array $where = [];
    private array $join = [];
    private array $union = [];
    private array $unionAll = [];
    private array $group = [];
    private array $order = [];

    public function __construct(array $fields)
    {
      $this->fields = $fields;
      return $this;
    }

    public function from(string $table, string $alias = null)
    {
      if(!$alias){
        $this->table[] = $table;
      }else{
        $this->table[] = "$table AS $alias";
      }
      return $this;
    }

    public function where(array $where)
    {
      $this->where = $where;
      return $this;
    }

    public function join(array $join)
    {
      $this->join = $join;
      return $this;
    }

    public function __toString(): string
    {
      $properties = get_object_vars($this);

      return sprintf("SELECT %s FROM %s WHERE %s",
      join(', ', $this->fields),
      join(', ', $this->table),
      join(' AND ', $this->where));
    }
  }