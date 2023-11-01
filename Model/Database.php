<?php
class Database
{
  protected $connection;

  public function __construct()
  {
    global $config; // Access the $config array from config.php

    $host = $config['host'];
    $database = $config['database'];
    $username = $config['username'];
    $password = $config['password'];

    $this->connection = new mysqli($host, $username, $password, $database);

    if ($this->connection->connect_error) {
      die("Connection failed: " . $this->connection->connect_error);
    }
  }
  public function select($query = "", $params = [])
  {
    try {
      $stmt = $this->executeStatement($query, $params);
      $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
      $stmt->close();
      return $result;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
  private function executeStatement($query = "", $params = [])
  {
    try {
      $stmt = $this->connection->prepare($query);
      if ($stmt === false) {
        throw new Exception("Unable to do prepared statement: " . $query);
      }
      if ($params) {
        $stmt->bind_param($params[0], $params[1]);
      }
      $stmt->execute();
      return $stmt;
    } catch (Exception $e) {
      throw new Exception($e->getMessage());
    }
  }
}
?>