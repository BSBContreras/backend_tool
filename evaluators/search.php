<?php

require_once('EvaluatorsController.php');

class Search extends EvaluatorsController {

  public static function request($data) {
    try {
      if(!isset($data->query)){
        self::error(['id' => 0, 'detail' => 'Few Arguments']);
      }

      if(!is_string($data->query) || empty($data->query)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      $query = '%'.$data->query.'%';

      return self::search($query);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $response = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $response
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Search::response(Search::request($data));

?>