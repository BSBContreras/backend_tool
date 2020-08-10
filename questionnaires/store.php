<?php

require_once('QuestionnairesController.php');

class Store extends QuestionnairesController {

  public static function request($questionnaire) {
    try {
      if(!isset($questionnaire->name) || !isset($questionnaire->manager_id)){
        throw new Exception('few arguments');
      }
      return self::store($questionnaire);
    } catch(Exception $e) {
      self::error($e->getMessage());
    }
  }

  public static function response($questionnaire) {
    http_response_code(201);

    $data = self::show($questionnaire->id)->fetch();

    echo json_encode([
      'status' => 'success',
      'docs' => [
        'id' => $data->id,
        'name' => $data->name,
        'detail' => $data->detail,
        'manager' => [
          'id' => $data->manager_id,
          'name' => $data->manager_name,
          'email' => $data->manager_email
        ]
      ]
    ]);
  }
}

$data = json_decode(file_get_contents("php://input"));

Store::response(Store::request($data));

?>