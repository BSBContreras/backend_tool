<?php

require_once('TasksController.php');

class Pages extends TasksController {

  public static function request($data) {
    try {
      if(!isset($data->task_id)){
        throw new Exception('few arguments');
      }
      return self::pages($data->task_id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $response = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $response
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Pages::response(Pages::request($data));

?>