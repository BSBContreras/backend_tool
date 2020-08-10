<?php 

require_once('../connection/Connect.php');

class AnswerTypesController extends Connect {
  private static $table_name = '_answer_types';
  private static $PK_table = 'id';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `name`, `detail`
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

  public static function show($id) {
    try {
      $sql = 'SELECT *
              FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

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

  public static function store($criterion) {
    try {
      $date = date("Y-m-d H:i:s");

      $sql = 'INSERT INTO '.self::$table_name.'
              VALUES (NULL, :name, :date, :date)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $criterion->name, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        return self::getConnection()->lastInsertId();
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($criterion) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $criterion->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($data) {
    try {
      $sql = 'UPDATE  `_questionnaire-question` SET
              `answer_type_id` = :answer_type_id,
              `updated_at` = NULL
              WHERE `questionnaire_id` = :questionnaire_id
              AND `question_id` = :question_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':answer_type_id', $data->answer_type_id, PDO::PARAM_INT);
      $stmt->bindParam(':questionnaire_id', $data->questionnaire_id, PDO::PARAM_INT);
      $stmt->bindParam(':question_id', $data->question_id, PDO::PARAM_INT);

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

  public static function questions($criterion) {
    try {
      $sql = 'SELECT 
              question.`id`,
              question.`text`,
              criterion.`id` AS criterion_id,
              criterion.`name` AS criterion,
              element_1.`id` AS element_1_id, 
              element_1.`name` AS element_1,
              element_2.`id` AS element_2_id,
              element_2.`name` AS element_2
              FROM '.self::$table_two_foreign.' AS question
              JOIN '.self::$table_three_foreign.' AS criterion
              ON question.'.self::$table_two_foreign_id_1.' = criterion.'.self::$table_three_foreign_pk.'
              LEFT OUTER JOIN '.self::$table_four_foreign.' AS element_1
              ON question.'.self::$table_two_foreign_id_2.' = element_1.'.self::$table_four_foreign_pk.'
              LEFT OUTER JOIN '.self::$table_four_foreign.' AS element_2
              ON question.'.self::$table_two_foreign_id_3.' = element_2.'.self::$table_four_foreign_pk.'
              WHERE criterion.'.self::$table_three_foreign_pk.' = :criterion_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':criterion_id', $criterion->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        return $stmt;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

}

?>