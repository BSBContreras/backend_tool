<?php

require_once('ElementsController.php');

class Store extends ElementsController {

  public static function request($element) {
    try {
      if(!isset($element->name)){
        throw new Exception('few arguments');
      }
      return self::store($element);
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