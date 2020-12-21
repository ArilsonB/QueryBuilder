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

  $limit = 9;
  if(isset($_GET['p']) && $page = $_GET['p']){
    $offset = ($page-1) * $limit; 
  }else{
    $offset = 0;
  }

  $query = $query->select(["p.id","p.title","p.content"])->from('posts','p')
  ->order([array("field" => "p.id", "dir" => "ASC")])
  ->limit($limit)
  ->offset($offset);

  if($query = $query->execute()){
    foreach($query->fetch() as $post){
      echo<<<"HTML"
        <p>
          <h1>$post[id] – $post[title]</h1>
          <p>$post[content]</p>
        </p>
      HTML;
    }
  }
  