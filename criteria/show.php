<?php

require_once('CriteriaController.php');

class Show extends CriteriaController {

  public static function request($criterion) {
    try {
      if(!isset($criterion->id)){
        throw new Exception('few arguments');
      }
      return self::show($criterion->id)->fetch();
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

Show::response(Show::request($data));

?>