<?php

const ERROR_LOG_FILE = "/home/ssimo/Rendu/pool_php_rush/errors.log";

function connect_db($servername, $username, $password, $port, $dbname)
{
  try {
    $bdd = new PDO("mysql:host=$servername;port=$port;dbname=$dbname;charset=utf8", $username, $password);
    return $bdd;
  } catch (PDOException $e) {
    echo "PDO ERROR: " . $e->getMessage() . "storage in " . ERROR_LOG_FILE . "\n";
    error_log("PDO ERROR: ".$e->getMessage()." storage in " .ERROR_LOG_FILE . "\n", 3, ERROR_LOG_FILE);
  }
}
