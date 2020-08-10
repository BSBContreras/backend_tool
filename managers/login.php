<?php

require_once('ManagersController.php');

class Login extends ManagersController {

  public static function request($manager) {
    try {
      if(
        !isset($manager->email) ||
        !isset($manager->password)
      ){
        throw new Exception('Few arguments');
      }

      if(!is_string($manager->password)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(strlen($manager->password) < 8 || strlen($manager->password) > 32) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      $stmt = self::showByEmail($manager->email);
  
      if($stmt->rowCount() != 1) {
        self::error([ 'id' => 5, 'detail' => 'Unregistered account']);
      }

      $manager_db = $stmt->fetch();

      if(crypt($manager->password, $manager_db->password) == $manager_db->password) { 
        $manager->id = $manager_db->id;
        $manager->name = $manager_db->name;
        return $manager;
      } else {
        self::error([ 'id' => 6, 'detail' => 'Invalid Password']);
      }
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

Login::response(Login::request($data));

?>