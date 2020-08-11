<?php

require_once('ElementsController.php');

class Questions extends ElementsController {

  public static function request($data) {
    try {
      if(!isset($data->id)){
        throw new Exception('few arguments');
      }
      return self::questions($data->id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    $questions = $response->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $questions
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Questions::response(Questions::request($data));

?>