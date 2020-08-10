<?php

require_once('DesignersController.php');

class Index extends DesignersController {

  public static function request() {
    try {
      return self::index()->fetchAll();
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