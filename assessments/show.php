<?php

require_once('AssessmentsController.php');
require_once('../questionnaires/QuestionnairesController.php');
require_once('../managers/ManagersController.php');

class Show extends AssessmentsController {

  public static function request($assessment) {
    try {
      if(!isset($assessment->id)){
        throw new Exception('few arguments');
      }

      return self::show($assessment->id);      
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $assessment = $stmt->fetch();

    $task_size = self::tasks($assessment->id)->count();

    $questionnaire = QuestionnairesController::show($assessment->questionnaire_id)->fetch();
    $manager = ManagersController::show($questionnaire->manager_id)->fetch();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $assessment->id,
        'name' => $assessment->name,
        'detail' => $assessment->detail,
        'created_at' => $assessment->created_at,
        'updated_at' => $assessment->created_at,
        'completed_at' => $assessment->completed_at,
        'questionnaire' => $questionnaire,
        'manager' => $manager
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Show::response(Show::request($data));

?>