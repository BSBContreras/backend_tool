<?php

require_once('CriteriaController.php');

class Index extends CriteriaController {

  public static function request() {
    try {
      $response = array();
      $result = self::index();

      while($row = $result->fetch()) {
        $response[] = [
          'id' => $row->id,
          'name' => $row->name,
          'questions' => self::questions($row)->rowCount()
        ];
      }

      return $response;
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

Index::response(Index::request());

?>