<?php

header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, origin, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// define('SERVERNAME', '10.100.116.219');
// define('USERNAME', 'w86cond_met_pand');
// define('PASSWORD', '#LNz65ul');
// define('DBNAME', 'w86cond_met_pand');

define('SERVERNAME', 'joinsoft.com.br');
define('USERNAME', 'joinsoft_coinspc');
define('PASSWORD', '7JGFrK58Oolv');
define('DBNAME', 'join_coinspc');

abstract class Connect {
  private static $connection;

  public static function getConnection() {
    try {
      if(!isset(self::$connection)) {
        self::$connection = new PDO('mysql:host='.SERVERNAME.';dbname='.DBNAME, USERNAME, PASSWORD);
        // set the PDO error mode to exception
        self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      }
  
      return self::$connection;
    } catch(PDOException $e) {
      throw new Exception($e->getMessage());   
    }
  }

  public static function error($message) {
    http_response_code(200);
    echo json_encode([
      'status' => 'error',
      'docs' => is_string($message) ? ['id' => 0, 'detail' => $message] : $message
    ]);
    die();
  }
}

?>