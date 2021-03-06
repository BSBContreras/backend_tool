<?php

require_once('QuestionsController.php');

class Update extends QuestionsController {

  public static function request($question) {
    try {
      if(!isset($question->id) || !isset($question->text) || !isset($question->criterion_id)){
        throw new Exception('few arguments');
      }
      return self::update($question);
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

Update::response(Update::request($data));

?>