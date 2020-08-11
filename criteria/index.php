<?php

require_once('CriteriaController.php');

class Index extends CriteriaController {

  public static function request() {
    try {
      return self::index();
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    $criteria = $response->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $criteria
    ]);
  }
}

Index::response(Index::request());

?>