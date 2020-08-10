<?php

require_once('TasksController.php');

class Store extends TasksController {

  public static function request($task) {
    try {
      if(!isset($task->name) || !isset($task->pages[0])){
        throw new Exception('few arguments');
      }
      $task_id = self::store($task);
      $task->id = $task_id;
      return self::sync($task);
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

Store::response(Store::request($data));

?>