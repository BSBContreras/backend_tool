<?php

require_once('CriteriaController.php');

class Store extends CriteriaController {

  public static function request($criterion) {
    try {
      if(!isset($criterion->name)){
        throw new Exception('few arguments');
      }

      if(!is_string($criterion->name) || empty($criterion->name)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(isset($criterion->detail) && !is_string($criterion->detail)) {
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }      

      if(empty($criterion->detail)) {
        $criterion->detail = NULL;
      }

      return self::store($criterion);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($criterion) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => $criterion
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>