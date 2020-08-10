<?php

require_once('DesignersController.php');

class Store extends DesignersController {

  public static function request($designer) {
    try {
      if(
        !isset($designer->name) || 
        !isset($designer->email)
      ){
        throw new Exception('few arguments');
      }

      if(!is_string($designer->name) || empty($designer->name)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(!is_string($designer->email) || empty($designer->email)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }
  
      if(self::showByEmail($designer->email)->rowCount() > 0) {
        self::error([ 'id' => 2, 'detail' => 'This email has already been registered!' ]);
      }

      return self::store($designer);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($designer) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $designer->id,
        'name' => $designer->name,
        'email' => $designer->email,
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>