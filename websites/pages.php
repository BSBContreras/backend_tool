<?php

require_once('WebsitesController.php');

class Pages extends WebsitesController {

  public static function request($website) {
    try {
      if(!isset($website->id)){
        throw new Exception('few arguments');
      }
      
      return self::pages($website->id)->fetchAll();
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

Pages::response(Pages::request($data));

?>