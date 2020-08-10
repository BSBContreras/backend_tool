<?php

require_once('ManagersController.php');

class Store extends ManagersController {

  public static function request($manager) {
    try {
      if(
        !isset($manager->name) || 
        !isset($manager->email) ||
        !isset($manager->password)
      ){
        throw new Exception('few arguments');
      }

      if(!is_string($manager->password)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(strlen($manager->password) < 8 || strlen($manager->password) > 32) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }
  
      if(self::showByEmail($manager->email)->rowCount() > 0) {
        self::error([ 'id' => 2, 'detail' => 'This email has already been registered!' ]);
      }

      return self::store($manager);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($manager) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $manager->id,
        'name' => $manager->name,
        'email' => $manager->email,
        'password' => $manager->password
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>