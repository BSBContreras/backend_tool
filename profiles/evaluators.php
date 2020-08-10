<?php

require_once('ProfilesController.php');

class Evaluators extends ProfilesController {

  public static function request($profile) {
    try {
      if(!isset($profile->id)){
        throw new Exception('few arguments');
      }
      return self::evaluators($profile)->fetchAll();
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

Evaluators::response(Evaluators::request($data));

?>