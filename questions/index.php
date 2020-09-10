<?php

require_once('QuestionsController.php');
require_once('../criteria/CriteriaController.php');
require_once('../elements/ElementsController.php');

class Index extends QuestionsController {

  public static function request() {
    try {
      return self::index();
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($data) {

    $questions = [];
    
    while($question = $data->fetch()) {
      $questions[] = [
        'id' => $question->id,
        'text' => $question->text,
        'criterion' => CriteriaController::show($question->criterion_id)->fetch()->name,
        'element_1' => ElementsController::show($question->element_1_id)->fetch()->name,
        'element_2' => ElementsController::show($question->element_2_id)->fetch()->name,
      ];
    }

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $questions
    ]);
  }
}

Index::response(Index::request());

?>