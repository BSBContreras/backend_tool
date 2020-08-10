<?php

require_once('TasksController.php');

class Sync extends TasksController {

  public static function request($task) {
    try {
      if(!isset($task->id) || !isset($task->pages)){
        throw new Exception('few arguments');
      }
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

Sync::response(Sync::request($data));

?>