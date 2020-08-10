<?php

require_once('QuestionnairesController.php');

class Delete extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(!isset($questionnaire->id)){
        throw new Exception('few arguments');
      }
      return self::delete($questionnaire);
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

Delete::response(Delete::request($data));

?>