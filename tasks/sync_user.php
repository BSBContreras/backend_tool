<?php

require_once('TasksController.php');

class SyncUser extends TasksController {

  public static function request($task) {
    try {
      if(!isset($task->task_id) || !isset($task->evaluator_id) || !isset($task->pages)){
        throw new Exception('few arguments');
      }
      return self::syncUser($task);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => $response
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

SyncUser::response(SyncUser::request($data));

?>