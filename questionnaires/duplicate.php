<?php

require_once('QuestionnairesController.php');

class Duplicate extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(
        !isset($questionnaire->id) || 
        !isset($questionnaire->name
      )){
        throw new Exception('few arguments');
      }
      return self::Duplicate($questionnaire);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($questionnaire) {
    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $questionnaire->id,
        'name' => $questionnaire->name,
        'detail' => $questionnaire->detail,
        'manager_id' => $questionnaire->manager_id
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Duplicate::response(Duplicate::request($data));

?>