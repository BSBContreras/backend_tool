<?php 

require_once('../connection/Connect.php');

class QuestionsController extends Connect {
  private static $PK_table = 'id';
  private static $table_name = '_questions';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `text`, `criterion_id`, `element_1_id`, `element_2_id`
              FROM '.self::$table_name;
    
      $stmt = self::getConnection()->prepare($sql);

      if($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        return $stmt;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function show($question_id) {
    try {
      $sql = 'SELECT *
              FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :question_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);

      if($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_OBJ); 
        return $stmt;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function store($question) {
    try {

      $sql = 'INSERT INTO '.self::$table_name.'
              (`text`, `criterion_id`, `element_1_id`, `element_2_id`) VALUES
              (:text, :criterion_id, :element_1_id, :element_2_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':criterion_id', $question->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $question->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $question->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $question->text, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        $question->id = self::getConnection()->lastInsertId();
        return $question;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($question_id) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :question_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($question) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($question->element_1_id) && $question->element_1_id = NULL;
      empty($question->element_2_id) && $question->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $question->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $question->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $question->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $question->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $question->text, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);

      if($stmt->execute()) {
        return true;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function updateTimeStamp($id) {
    try {
      $date = date("Y-m-d H:i:s");

      $sql = 'UPDATE '.self::$table_name.' SET
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return true;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }
}

?>