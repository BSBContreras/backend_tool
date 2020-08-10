<?php

require_once('AssessmentsController.php');
require_once('../questionnaires/QuestionnairesController.php');

class Store extends AssessmentsController {

  public static function request($assessment) {
    try {
      if(
        !isset($assessment->name) || 
        !isset($assessment->questionnaire_id) ||
        !isset($assessment->tasks_id[0]) ||
        !isset($assessment->evaluators_id[0])
      ) {
        throw new Exception('few arguments');
      }
      return self::store($assessment);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($assessment) {
    $questionnaire = QuestionnairesController::show($assessment->questionnaire_id)->fetch();

    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $assessment->id,
        'name' => $assessment->name,
        'detail' => $assessment->detail,
        'manager_id' => $questionnaire->manager_id,
        'questionnaire_id' => $assessment->questionnaire_id
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

$data->manager_id = 1;

Store::response(Store::request($data));

?>