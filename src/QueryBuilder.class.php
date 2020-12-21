<?php

  namespace MyB;
  require_once('Builder/Select.php');
  use MyB\QueryBuilder\Select as Select;

  class QueryBuilder {
    protected $pdo;

    public function __construct(\PDO $pdo){
      try {
        $this->pdo = $pdo;
      }catch(\PDOException $e){
        return exit('Error');
      }
    }

    public function select($fields = '*'){
      $fieldsArray = [];
      if(is_string($fields)){
        $fieldsArray[] = $fields;
        $fields = $fieldsArray;
      }
      $select = new Select($fields, $this->pdo);
      return $select;
    }
  }