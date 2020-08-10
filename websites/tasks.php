<?php

require_once('WebsitesController.php');

class Tasks extends WebsitesController {

  public static function request($website) {
    try {
      if(!isset($website->website_id)){
        throw new Exception('few arguments');
      }

      return self::tasks($website->website_id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $data = $stmt->fetchAll();

    $map = [];

    foreach($data as $item) {

      $temp = $map[$item->task_id];

      if($temp == NULL) {

        $map[$item->task_id] = $item;

      } else {

        if($temp->assessment_completed_at != NULL)  {

          $map[$item->task_id] = $item;

        }

      }

    }

    $available = [];
    
    $unavailable = [];

    foreach($map as $item) {

      $task = [
        'id' => $item->task_id,
        'name' => $item->task_name,
        'detail' => $item->task_detail,
      ];

      if(!$item->assessment_id || $item->assessment_completed_at) {

        $available[] = $task;

      } else {

        $unavailable[] = $task;
        
      }
    }

    http_response_code(200);
    echo json_encode([
      'status' => 'success',
      'docs' => [
        'available' => $available,
        'unavailable' => $unavailable
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Tasks::response(Tasks::request($data));

?>