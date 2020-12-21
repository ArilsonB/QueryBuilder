<?php
  require_once './src/QueryBuilder.class.php';

  $config = [
    "driver" => "mysql",
    "host" => "localhost",
    "port" => "3306",
    "database" => "MyBlog",
    "username" => "root",
    "password" => "root"
  ];

  $pdo = sprintf('%s:host=%s;port=%s;dbname=%s;connect_timeout=15', $config['driver'], $config['host'], $config['port'], $config['database']);

  try {
    $pdo = new PDO($pdo, $config['username'], $config['password']);
  }catch(PDOException $e){
    exit($e->getMessage());
  }

  $query = new MyB\QueryBuilder($pdo);

  echo $query->select()->from('aaa','bbb')->where([
    "user" => "ArilsonB"
  ]);
?>