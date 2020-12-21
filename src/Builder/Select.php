<?php

  namespace MyB\QueryBuilder;

  class Select {
    protected $db;
    private array $fields = [];
    private array $table = [];
    private array $where = [];
    private array $join = [];
    private array $union = [];
    private array $unionAll = [];
    private array $group = [];
    private array $order = [];
    private $query;

    public function __construct(array $fields, $db = null)
    {
      $this->db = $db;
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

    public function limit(int $limit = 10){

    }

    public function execute(){
      $query = $this->db->prepare($this->__toString());
      if($query->execute()){
        $this->query = $query;
      }
      return $this;
    }

    public function fetch($fetch_mode = 'assoc'){
      return $this->query->fetch(\PDO::FETCH_ASSOC);
    }

    public function __toString(): string
    {
      $output = str_replace('=', ": ", http_build_query($this->where, '', ', '));

      echo $output;

      $properties = get_object_vars($this);

      return sprintf("SELECT %s FROM %s ",
      join(', ', $this->fields),
      join(', ', $this->table),
    /*join(' AND ', $this->where)*/);
    }
  }