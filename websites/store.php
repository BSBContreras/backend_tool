<?php

require_once('WebsitesController.php');
require_once('../managers/ManagersController.php');
require_once('../designers/DesignersController.php');

class Store extends WebsitesController {

  public static function request($website) {
    try {
      if(
        !isset($website->name) || 
        !isset($website->url) || 
        !isset($website->designer_id) ||
        !isset($website->manager_id)
      ){
        throw new Exception('few arguments');
      }

      if(!is_string($website->name) || empty($website->name)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      if(!is_string($website->url) || empty($website->url)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      $stmt = ManagersController::show($website->manager_id);
  
      if($stmt->rowCount() != 1) {
        self::error([ 'id' => 5, 'detail' => 'Unregistered account']);
      }

      $stmt = DesignersController::show($website->designer_id);
  
      if($stmt->rowCount() != 1) {
        self::error([ 'id' => 5, 'detail' => 'Unregistered account']);
      }

      return self::store($website);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($website) {

    http_response_code(201);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $website->id,
        'name' => $website->name,
        'url' => $website->url
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>