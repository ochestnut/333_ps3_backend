<?php
$host = 'sql306.infinityfree.com';
$db = 'if0_35298826_music_db';
$user = 'if0_35298826';
$pass = 'sk8kbk';

$conn = mysqli_connect($host, $user, $pass, $db);

// Check for connection errors
if ($conn->connect_errno) {
  echo "Failed to connect to MySQL: " . $conn->connect_error;
  exit();
} else {
  echo "";
}
?>