<?php

  namespace ArilsonB;
  use \PDO as PDO;
  use \PDOException as PDOException;

  class QueryBuilder extends PDO {
    protected $conn;
    private $operators = [
      '=', 
      '>', 
      '>=', 
      '<', 
      '<=', 
      '!=', 
      '<>', 
      'LIKE',
      'NOT LIKE', 
      'IS NULL', 
      'IS NOT NULL', 
      'IN', 
      'NOT IN',
      'BETWEEN',
      'NOT BETWEEN',
      'REGEXP'
    ];

    public function __construct(?Array $options)
    {
      if(isset($options['connection'])){
        extract($options['connection']);
        switch($options['client']){
          case 'sqlite':
            $client = "sqlite";
            $dbfile = isset($file) ? $file : realpath( __DIR__ . "/database.sqlite" );
            $conn = sprintf("$client:$file");
            break;
          case 'pgsql':
            $client = "pgsql";
            break;
          case 'oracle':
            $client = "oci";
            break;
          default:
            $client = "mysql";
            $port = isset($port) ? $port : 3306;
            $timeout = isset($timeout) ? $timeout : 15;
            $charset = isset($charset) ? $charset : 'utf8mb4';
            $conn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s;connect_timeout=%s', $hostname, $port, $database, $charset, $timeout);
        }
        try {
          if(isset($username,$password)){
            parent::__construct($conn, $username, $password);
          }else{
            parent::__construct($conn);
          }
          if(isset($options)){
            foreach($options as $option => $value){
              parent::setAttribute($option, $value);
            }
          }
  
        } catch (PDOException $e){
          return exit($e->getMessage());
        }
      }else{
        exit (new PDOException("Fatal error, connection falied."));
      }
    }

    public function Select(){

    }

    protected function generate_where()
    {

    }

    protected function error(){

    }

  }