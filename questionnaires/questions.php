<?php

require_once('QuestionnairesController.php');

class Questions extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(!isset($questionnaire->id)){
        throw new Exception('few arguments');
      }
      return self::questions($questionnaire)->fetchAll();
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

Questions::response(Questions::request($data));

?>