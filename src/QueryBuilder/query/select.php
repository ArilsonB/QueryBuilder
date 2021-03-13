<?php
  namespace ArilsonB\QueryBuilder;
  use \PDO;

  class Select {
    protected $db;
    protected $sql;

    public function __construct(?Array $fields, PDO $db)
    {
      $this->db = $db;
      $this->fields = $fields;

      return $this;
    }
  }