<?php

require_once('ManagersController.php');

class Assessments extends ManagersController {

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

      return self::assessments($manager_id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($data) {
    $assessments = $data->fetchAll();

    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => $assessments
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Assessments::response(Assessments::request($data));

?>