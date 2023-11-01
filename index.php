<?php

define('PROJECT_ROOT_PATH', '/Users/owenchestnut/Desktop/COMP333/ps3/333_ps3_backend');

$uri = parse_url($_sSERVER['REQUEST_URI'], PHP_URL_PATH);

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers:*');
header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
header('Access-Control-Allow-Credentials: true');

$uri = explode('/', $uri);

if ($uri[2] == 'song') {

  require PROJECT_ROOT_PATH . "/songController.php";
  // create a new SongController which will connect to our child controller
  $objSongController = new SongController();
  //here we are creating a string by taking the 4th element of the 
  //exploded url and adding action, which corresponds to functions
  //in the SongController
  $strMethodName = $uri[3] . 'Action';
  //here we are calling the function 
  $objSongController->{$strMethodName}();
}


?>