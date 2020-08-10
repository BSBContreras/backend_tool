<?php

require_once('AssessmentsController.php');

class Evaluators extends AssessmentsController {

  public static function request($assessment) {
    try {
      if(!isset($assessment->id)){
        throw new Exception('few arguments');
      }
      return self::evaluators($assessment->id)->fetchAll();
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $response
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Evaluators::response(Evaluators::request($data));

?>