<?php

require_once('AnswerTypesController.php');

class Update extends AnswerTypesController {

  public static function request($data) {
    try {
      if(
        !isset($data->questionnaire_id) || 
        !isset($data->question_id) ||
        !isset($data->answer_type_id)
      ){
        throw new Exception('few arguments');
      }
      return self::update($data);
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

Update::response(Update::request($data));

?>