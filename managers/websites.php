<?php

require_once('ManagersController.php');

class Websites extends ManagersController {

  public static function request($data) {
    try {

      if(!isset($data->manager_id)){
        self::error(['id' => 0, 'detail' => 'Few Arguments']);
      }

      $manager_id = $data->manager_id;

      if(empty($manager_id)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      $stmt = self::show($manager_id);

      if($stmt->rowCount() != 1) {
        self::error([ 'id' => 5, 'detail' => 'Unregistered account']);
      }

      return self::websites($manager_id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $websites = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $websites
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

websites::response(websites::request($data));

?>