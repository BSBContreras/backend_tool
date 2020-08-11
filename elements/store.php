<?php

require_once('ElementsController.php');

class Store extends ElementsController {

  public static function request($element) {
    try {
      if(!isset($element->name)){
        throw new Exception('few arguments');
      }

      if(!is_string($element->name) || empty($element->name)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(isset($element->detail) && !is_string($element->detail)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }      

      if(empty($element->detail)) {
        $element->detail = NULL;
      }

      return self::store($element);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($element) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => $element
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>