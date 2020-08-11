<?php

require_once('EvaluatorsController.php');

class Store extends EvaluatorsController {

  public static function request($evaluator) {
    try {
      if(
        !isset($evaluator->name) || 
        !isset($evaluator->email)
      ){
        throw new Exception('few arguments');
      }

      if(!is_string($evaluator->name) || empty($evaluator->name)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(!is_string($evaluator->email) || empty($evaluator->email)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }
  
      if(self::showByEmail($evaluator->email)->rowCount() > 0) {
        self::error([ 'id' => 2, 'detail' => 'This email has already been registered!' ]);
      }

      $evaluator = self::store($evaluator);

      if(!isset($evaluator->profiles_ids) || empty($evaluator->profiles_ids)) {
        return $evaluator;
      }

      try {
        self::syncWithProfile($evaluator);
        return $evaluator;
      } catch(Exception $e) {
        self::delete($evaluator->id);
        self::error([ 'id' => 12, 'detail' => 'Unexpected error or Invalid format, Operation canceled' ]);
      }

    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($evaluator) {
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $evaluator->id,
        'name' => $evaluator->name,
        'email' => $evaluator->email,
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>