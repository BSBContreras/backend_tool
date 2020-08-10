<?php 

require_once('../connection/Connect.php');

class QuestionnairesController extends Connect {
  // This table
  private static $PK_table = 'id';
  private static $table_name = '_questionnaires';

  // pivot table questionnaire-question
  private static $table_one_foreign = '_questionnaire-question';
  private static $table_one_foreign_id_1 = 'question_id';
  private static $table_one_foreign_id_2 = 'questionnaire_id';

  // question table
  private static $table_two_foreign = '_questions';
  private static $table_two_foreign_pk = 'id';
  private static $table_two_foreign_id_1 = 'criterion_id';
  private static $table_two_foreign_id_2 = 'element_1_id';
  private static $table_two_foreign_id_3 = 'element_2_id';

  // criteria table
  private static $table_three_foreign = '_criteria';
  private static $table_three_foreign_pk = 'id';

  // elements table
  private static $table_four_foreign = '_elements';
  private static $table_four_foreign_pk = 'id';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `name`, `detail`, `manager_id`
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

  public static function store($questionnaire) {
    try {
      
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `detail`, `manager_id`) VALUES
              (:name, :detail, :manager_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $questionnaire->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $questionnaire->detail, PDO::PARAM_STR);     
      $stmt->bindParam(':manager_id', $questionnaire->manager_id, PDO::PARAM_INT);
      
      if($stmt->execute()) {
        $questionnaire->id = self::getConnection()->lastInsertId();
        return $questionnaire;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($questionnaire) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $questionnaire->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($questionnaire) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($questionnaire->detail) && $questionnaire->detail = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `name` = :name,
              `detail` = :detail,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $questionnaire->id, PDO::PARAM_INT);
      $stmt->bindParam(':name', $questionnaire->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $questionnaire->detail, PDO::PARAM_STR); 
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

  public static function duplicate($questionnaire) {
    try {
      $data = self::show($questionnaire->id)->fetch();

      $questionnaire_copy->name = $questionnaire->name;
      $questionnaire_copy->detail = $questionnaire->detail;
      $questionnaire_copy->manager_id = $data->manager_id;

      $questionnaire_copy = self::store($questionnaire_copy);

      $questionnaire_copy->attach = self::questions($questionnaire)->fetchAll();
      $questionnaire_copy->detach = [];

      if(self::sync($questionnaire_copy)) {
        return $questionnaire_copy;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function sync($questionnaire) {
    try {

      // Detaching questions
      $sql = 'DELETE FROM `'.self::$table_one_foreign.'`
              WHERE '.self::$table_one_foreign_id_1.' = :question_id
              AND '.self::$table_one_foreign_id_2.' = :questionnaire_id';

      $stmt = self::getConnection()->prepare($sql);
      foreach($questionnaire->detach as $question_id) {
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->bindParam(':questionnaire_id', $questionnaire->id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query!');
        } 
      }

      // Attaching questions
      $sql = 'INSERT INTO `'.self::$table_one_foreign.'`
              (`questionnaire_id`, `question_id`, `answer_type_id`) VALUES
              (:questionnaire_id, :question_id, :answer_type_id)';

      $stmt = self::getConnection()->prepare($sql);
      
      foreach($questionnaire->attach as $question) {
        $stmt->bindParam(':questionnaire_id', $questionnaire->id, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $question->id, PDO::PARAM_INT);
        $stmt->bindParam(':answer_type_id', $question->answer_type_id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query!');
        } 
      }

      return self::updateTimeStamp($questionnaire->id);
    
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function updateTimeStamp($id) {
    try {
      $sql = 'UPDATE '.self::$table_name.' SET
              `updated_at` = NULL
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
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

  public static function questions($questionnaire) {
    try {

      /*
        SELECT 
        question.`id`,
        question.`text`,
        criterion.`id` AS criterion_id,
        criterion.`name` AS criterion,
        answer_type.`id` AS anser_type_id,
        answer_type.`name` AS anser_type_name,
        element_1.`id` AS element_1_id, 
        element_1.`name` AS element_1,
        element_2.`id` AS element_2_id,
        element_2.`name` AS element_2
        FROM _questions AS question
        JOIN _criteria AS criterion
        ON question.criterion_id = criterion.id
        LEFT OUTER JOIN _elements AS element_1
        ON question.element_1_id = element_1.id
        LEFT OUTER JOIN _elements AS element_2
        ON question.element_2_id = element_2.id
        JOIN `_questionnaire-question` AS questionnaire_question
        ON question.id = questionnaire_question.question_id
        JOIN _answer_types AS answer_type
        ON questionnaire_question.answer_type_id = answer_type.id
        WHERE questionnaire_question.questionnaire_id = :id;
      */
      
      $sql = 'SELECT 
              question.`id`,
              question.`text`,
              criterion.`id` AS criterion_id,
              criterion.`name` AS criterion,
              answer_type.`id` AS answer_type_id,
              answer_type.`name` AS answer_type_name,
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
              JOIN `'.self::$table_one_foreign.'` AS questionnaire_question
              ON question.'.self::$table_two_foreign_pk.' = questionnaire_question.'.self::$table_one_foreign_id_1.'
              JOIN `_answer_types` AS answer_type
              ON questionnaire_question.answer_type_id = answer_type.id
              WHERE questionnaire_question.'.self::$table_one_foreign_id_2.' = :questionnaire_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':questionnaire_id', $questionnaire->id, PDO::PARAM_INT);

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
}

?>
