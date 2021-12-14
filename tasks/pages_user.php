<?php

require_once('TasksController.php');

class PagesUser extends TasksController {

  public static function request($data) {
    try {
      if(!isset($data->task_id) || !isset($data->evaluator_id)){
        throw new Exception('few arguments');
      }
      return self::pagesUser($data->task_id, $data->evaluator_id);
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

PagesUser::response(PagesUser::request($data));

?>