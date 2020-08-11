<?php

require_once('ElementsController.php');

class Index extends ElementsController {

  public static function request() {
    try {
      return self::index();
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    $elements = $response->fetchAll();

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => $elements
    ]);
  }
}

Index::response(Index::request());

?>