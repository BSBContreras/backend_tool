<?php

require_once('CriteriaController.php');

class Delete extends CriteriaController {

  public static function request($data) {
    try {
      if(!isset($data->id)){
        throw new Exception('few arguments');
      }
      return self::delete($data->id);
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

Delete::response(Delete::request($data));

?>