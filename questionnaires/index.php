<?php

require_once('QuestionnairesController.php');

class Index extends QuestionnairesController {

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