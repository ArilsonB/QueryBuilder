<?php

  namespace ArilsonB;
  use \PDO as PDO;
  use \PDOException as PDOException;

  class QueryBuilder extends PDO {
    protected $connection;
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
            $connection = sprintf("$client:$file");
            break;
          case 'pgsql':
            $client = "pgsql";
            $connection = sprintf("");
            break;
          case 'oracle':
            $client = "oci";
            $connection = sprintf("");
            break;
          default:
            $client = "mysql";
            $port = isset($port) ? $port : 3306;
            $timeout = isset($timeout) ? $timeout : 15;
            $charset = isset($charset) ? $charset : 'utf8mb4';
            $hostname = isset($hostname) ? $hostname : 'localhost';
            $username = isset($username) ? $username : 'root';
            $password = isset($password) ? $password : '';
            $connection = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s;connect_timeout=%s', $hostname, $port, $database, $charset, $timeout);
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
          return trigger_error('Error occured while trying to connect with database ' . $e->getMessage(), E_USER_ERROR);
        }
      }else{
        return trigger_error('Fatal Error occured in connection settings.', E_USER_ERROR);
      }
    }

    public function select(...$fields)
    {

    }

    public function insert()
    {

    }

    public function delete()
    {

    }

    public function fields()
    {

    }

    protected function generate_where()
    {

    }

    protected function error(){

    }

    protected function debug($statement)
    {
      ob_start();
      $statement->debugDumpParams();
      $output = ob_get_contents() ?: null;
      ob_end_clean();
      return '<pre>'.htmlspecialchars($output).'</pre>';
    }

    function __destruct()
    {
      $this->conn = null;
    }

  }