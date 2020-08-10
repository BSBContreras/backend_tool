<?php

require_once('PagesController.php');

class Store extends PagesController {

  public static function request($page) {
    try {
      if(!isset($page->name) || !isset($page->url) || !isset($page->website_id) || !isset($page->elements_id)){
        throw new Exception('few arguments');
      }

      $page->elements_id[0] ? $page->element_1_id = $page->elements_id[0] : NULL;
      $page->elements_id[1] ? $page->element_2_id = $page->elements_id[1] : NULL;
      $page->elements_id[2] ? $page->element_3_id = $page->elements_id[2] : NULL;
      $page->elements_id[3] ? $page->element_4_id = $page->elements_id[3] : NULL;
      $page->elements_id[4] ? $page->element_5_id = $page->elements_id[4] : NULL;

      return self::store($page);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($response) {
    $page = self::show($response)->fetch();
    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $page->id,
        'name' => $page->name,
        'url' => $page->url
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>