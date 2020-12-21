<?php

  namespace MyB\QueryBuilder;

  class Select {
    protected $db;
    protected $sql;
    private array $fields = [];
    private array $table = [];
    private array $where = [];
    private array $arrWhere = [];
    private array $join = [];
    private array $union = [];
    private array $unionAll = [];
    private array $group = [];
    private array $order = [];
    private int $limit = 10;
    private int $offset = 0;


    public function __construct(array $fields, $db = null)
    {
      $this->db = $db;
      $this->fields = $fields;
      return $this;
    }

    public function from(string $table, string $alias = null)
    {
      if(!$alias){
        array_push($this->table, $table);
      }else{
        array_push($this->table, "$table AS $alias");
      }
      return $this;
    }

    public function where()
    {
      if(!is_array(func_get_arg(0))){
        $args = func_get_args();
        $column = func_get_arg(0);
        foreach($args as $arg){
          if(preg_match("/(>|>|=|!=)/",$arg, $match)){
            $condition = $match[0];
          }else{
            $condition = "=";
          }
        }
        $value = func_get_arg(1);
        array_push($this->where, array(
          "column" => $column,
          "condition" => $condition,
          "value" => $value
        ));
      }

      /*if(!$value){
        $this->where = $where;
      }else{
        array_push($this->where, array(
          "column" => $where,
          "condition" => $operator,
          "value" => $value
        ));
      }*/
      return $this;
    }

    public function whereNot()
    {

    }

    public function join(string $table, $on,string $type = "INNER")
    {
      array_push($this->join, [
        "type" => strtoupper($type),
        "table" => $table,
        "on" => (array)$on
      ]);
      return $this;
    }

    public function limit($limit = 10)
    {
      $this->limit = $limit;
      return $this;
    }

    public function offset($offset = 0){
      $this->offset = $offset;
      return $this;
    }

    public function order(array $order = [])
    { 
      $this->order = $order;
      return $this;
    }

    public function union()
    {

    }

    public function group(Array $group = []){
      $this->group = $group;
      return $this;
    }

    private function whereBuild($param = array(), $logic = "AND"){
      $array = array(
        "column" => "",
        "condition" => "",
        "value" => ""
      );

      if(is_array($param)){
        //var_dump($param);
      }

      return;
    }

    private function sql(){
      $sql = sprintf("SELECT %s FROM %s",
      join(', ', $this->fields),
      join(', ', $this->table));

      foreach($this->join as $join){
        $sql .= " $join[type] JOIN $join[table] ON " . join(" AND ", $join['on']);
      }

      if($this->where){
        $sql .= $this->whereBuild($this->where);
      }elseif($this->arrWhere){
        $sql .= " WHERE (";
        
        $sql .= implode(', ', array_map(function ($v, $k) {
          return sprintf("%s = :%s", $k, $k); 
        }, $this->where, array_keys($this->where)));

        $sql .= ")";
      }

      if($this->group){
        $group = " GROUP BY ";
        $group .= implode(', ', $this->group);
        $sql .= $group;
      }

      if($this->order){
        $orderBy = " ORDER BY ";
        $orderBy .= implode(', ', array_map(function($key){
          return sprintf("%s %s",$key['field'], $key['dir']);
        }, $this->order));
        $sql .= $orderBy;
      }

      if($this->limit){
        $limit = " LIMIT " . $this->limit;
        $sql .= $limit;
      }

      if($this->offset){
        $offset = " OFFSET " . $this->offset;
        $sql .= $offset;
      }

      return $sql;
    }

    public function execute(){

      $sql = $this->sql();

      $query = $this->db->prepare($sql);
      /*foreach($this->where as $where => &$val){
        $query->bindValue(":" . $where, $val, $this->getType($val));
      }*/

      if($query->execute()){
        $this->query = $query;
      }else{
        return false;
      }
      return $this;
    }

    public function fetch($fetch_mode = 'assoc'){
      return $this->query->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getType($val){
      if(is_string($val))return \PDO::PARAM_STR;
      if(is_int($val))return \PDO::PARAM_INT;
      if(is_bool($val))return \PDO::PARAM_BOOL;
    }

    public function __toString(): string
    {
      return $this->sql();
    }
  }