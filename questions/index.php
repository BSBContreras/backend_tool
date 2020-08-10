<?php

require_once('QuestionsController.php');
require_once('../criteria/CriteriaController.php');
require_once('../elements/ElementsController.php');

class Index extends QuestionsController {

  public static function request() {
    try {
      $response = array();
      $result = self::index();

      while($row = $result->fetch()) {
        $response[] = [
          'id' => $row->id,
          'text' => $row->text,
          'criterion' => CriteriaController::show($row->criterion_id)->fetch()['name'],
          'element_1' => ElementsController::show($row->element_1_id)->fetch()['name'],
          'element_2' => ElementsController::show($row->element_2_id)->fetch()['name'],
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