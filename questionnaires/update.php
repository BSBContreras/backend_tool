<?php

require_once('QuestionnairesController.php');

class Update extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(!isset($questionnaire->id) || !isset($questionnaire->name)){
        throw new Exception('few arguments');
      }
      return self::update($questionnaire);
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