<?php

require_once('ElementsController.php');

class Update extends ElementsController {

  public static function request($element) {
    try {
      if(!isset($element->id) || !isset($element->name)){
        throw new Exception('few arguments');
      }
      return self::update($element);
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