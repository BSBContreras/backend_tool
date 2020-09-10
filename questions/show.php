<?php

require_once('QuestionsController.php');
require_once('../criteria/CriteriaController.php');
require_once('../elements/ElementsController.php');

class Show extends QuestionsController {

  public static function request($data) {
    try {
      if(!isset($data->id)){
        throw new Exception('few arguments');
      }

      if(!is_numeric($data->id)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      return self::show($data->id);      
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $data = $stmt->fetch();

    $question = [
      'id' => $data->id,
      'text' => $data->text,
      'created_at' => $data->created_at,
      'updated_at' => $data->created_at,
      'criterion' => CriteriaController::show($data->criterion_id)->fetch(),
      'element_1' => ElementsController::show($data->element_1_id)->fetch(),
      'element_2' => ElementsController::show($data->element_2_id)->fetch()
    ];

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $question
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Show::response(Show::request($data));

?>