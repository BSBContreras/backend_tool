<?php

require_once('CriteriaController.php');

class Show extends CriteriaController {

  public static function request($data) {
    try {
      if(!isset($data->id)){
        throw new Exception('few arguments');
      }
      return self::show($data->id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    $criterion = $response->fetch();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $criterion
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Show::response(Show::request($data));

?>