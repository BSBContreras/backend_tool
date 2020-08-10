<?php

require_once('QuestionnairesController.php');

class Sync extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(
        !isset($questionnaire->id) || 
        !isset($questionnaire->attach) || 
        !isset($questionnaire->detach)){
        throw new Exception('few arguments');
      }
   
      return self::sync($questionnaire);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => $response
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

// echo file_get_contents("php://input");

Sync::response(Sync::request($data));

?>