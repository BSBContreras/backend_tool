<?php

require_once('ElementsController.php');

class Show extends ElementsController {

  public static function request($element) {
    try {
      if(!isset($element->id)){
        throw new Exception('few arguments');
      }
      return self::show($element->id)->fetch();
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