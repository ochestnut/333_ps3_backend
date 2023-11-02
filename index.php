<?php
require __DIR__ . "/inc/bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (isset($uri[2])) {
  if ($uri[2] === 'song') {
    // Handle song-related requests
    require PROJECT_ROOT_PATH . "/Controller/Api/SongController.php";
    $objFeedController = new SongController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
  } elseif ($uri[2] === 'user') {
    // Handle user-related requests
    require PROJECT_ROOT_PATH . "/Controller/Api/UserController.php";
    $objFeedController = new UserController();
    $strMethodName = $uri[3] . 'Action';
    $objFeedController->{$strMethodName}();
  } else {
    // Invalid request
    header("HTTP/1.1 404 Not Found");
    exit();
  }
} else {
  // Invalid request
  header("HTTP/1.1 404 Not Found");
  exit();
}
?>