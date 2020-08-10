<?php

require_once('ManagersController.php');

class Questionnaires extends ManagersController {

  public static function request($data) {
    try {

      if(!isset($data->manager_id)){
        self::error(['id' => 0, 'detail' => 'Few Arguments']);
      }

      $manager_id = $data->manager_id;

      if(empty($manager_id)){
        self::error([ 'id' => 7, 'detail' => 'Invalid Format' ]);
      }

      $stmt = self::show($manager_id);

      if($stmt->rowCount() != 1) {
        self::error([ 'id' => 5, 'detail' => 'Unregistered account']);
      }

      return self::questionnaires($manager_id);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($stmt) {
    $data = $stmt->fetchAll();

    $map = [];

    foreach($data as $item) {

      $temp = $map[$item->questionnaire_id];

      if($temp == NULL) {

        $map[$item->questionnaire_id] = $item;

      } else {

        if($temp->assessment_completed_at != NULL)  {

          $map[$item->questionnaire_id] = $item;

        }

      }

    }

    $available = [];
    
    $unavailable = [];

    foreach($map as $item) {

      $questionnaire = [
        'id' => $item->questionnaire_id,
        'name' => $item->questionnaire_name,
        'detail' => $item->questionnaire_detail,
        'manager_id' => $item->questionnaire_manager_id
      ];

      if(!$item->assessment_id || $item->assessment_completed_at) {

        $available[] = $questionnaire;

      } else {

        $unavailable[] = $questionnaire;
        
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

Questionnaires::response(Questionnaires::request($data));

?>