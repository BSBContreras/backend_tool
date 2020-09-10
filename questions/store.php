<?php

require_once('QuestionsController.php');

class Store extends QuestionsController {

  public static function request($data) {
    try {
      if(!isset($data->text) || !isset($data->criterion_id)){
        throw new Exception('few arguments');
      }

      if(!is_string($data->text) || empty($data->text) || strlen($data->text) > 300){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(!is_numeric($data->criterion_id)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(isset($data->element_1_id) && !is_numeric($data->element_1_id)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(isset($data->element_2_id) && !is_numeric($data->element_2_id)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(!isset($data->element_1_id) || empty($data->element_1_id)) {
        $data->element_1_id = NULL;
      }

      if(!isset($data->element_2_id) || empty($data->element_2_id)) {
        $data->element_2_id = NULL;
      }

      return self::store($data);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($question) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $question->id,
        'text' => $question->text,
        'criterion_id' => $question->criterion_id,
        'element_1_id' => $question->element_1_id,
        'element_2_id' => $question->element_2_id
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>