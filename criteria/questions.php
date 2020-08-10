<?php

require_once('CriteriaController.php');

class Questions extends CriteriaController {

  public static function request($criterion) {
    try {
      if(!isset($criterion->id)){
        throw new Exception('few arguments');
      }
      return self::questions($criterion)->fetchAll();
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