<?php

require_once('QuestionsController.php');
require_once('../criteria/CriteriaController.php');
require_once('../elements/ElementsController.php');

class Show extends QuestionsController {

  public static function request($question) {
    try {
      if(!isset($question->id)){
        throw new Exception('few arguments');
      }

      $result = self::show($question->id)->fetch();

      return [
        'id' => $result->id,
        'text' => $result->text,
        'created_at' => $result->created_at,
        'updated_at' => $result->created_at,
        'criterion' => CriteriaController::show($result->criterion_id)->fetch(),
        'element_1' => ElementsController::show($result->element_1_id)->fetch(),
        'element_1' => ElementsController::show($result->element_2_id)->fetch()
      ];
      
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

Show::response(Show::request($data));

?>