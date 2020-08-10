<?php

require_once('CriteriaController.php');

class Store extends CriteriaController {

  public static function request($criterion) {
    try {
      if(!isset($criterion->name)){
        throw new Exception('few arguments');
      }
      return self::store($criterion);
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

Store::response(Store::request($data));

?>