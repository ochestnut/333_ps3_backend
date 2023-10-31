<?php

$config = [
  'host' => 'sql306.infinityfree.com',
  'database' => 'if0_35298826_music_db',
  'username' => 'if0_35298826',
  'password' => 'sk8kbk',
];

class Database
{
  protected $connection;

  public function __construct($config)
  {
    $host = $config['host'];
    $database = $config['database'];
    $username = $config['username'];
    $password = $config['password'];

    $this->connection = new mysqli($host, $username, $password, $database);

    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
  }

  public function query($sql)
  {
    // Execute a database query using $this->connection
    return $this->connection->query($sql);
  }

  public function close()
  {
    // Close the database connection
    $this->connection->close();
  }
}
?>