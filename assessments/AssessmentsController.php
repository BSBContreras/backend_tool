<?php 

require_once('../connection/Connect.php');

class AssessmentsController extends Connect {
  // this table
  private static $PK_table = 'id';
  private static $table_name = '_assessments';

  // table pivot assessment-task
  private static $table_one_foreign = '_assessment-task';
  private static $table_one_foreign_pk = 'id';
  private static $table_one_foreign_id_1 = 'assessment_id';
  private static $table_one_foreign_id_2 = 'task_id';

  // table pivot assessment-evaluator
  private static $table_two_foreign = '_assessment-evaluator';
  private static $table_two_foreign_pk = 'id';
  private static $table_two_foreign_id_1 = 'assessment_id';
  private static $table_two_foreign_id_2 = 'evaluator_id';

  // table evaluators
  private static $table_three_foreign = '_evaluators';
  private static $table_three_foreign_pk = 'id';  

  public static function index() {
    try {
      $sql = 'SELECT 
                assessment.`id`, 
                assessment.`name`, 
                assessment.`detail`, 
                assessment.`questionnaire_id`,
                questionnaire.`manager_id`
              FROM '.self::$table_name.' AS assessment
              JOIN `_questionnaires` AS questionnaire
              ON questionnaire.id = assessment.questionnaire_id';
    
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

  public static function store($assessment) {
    try {
      // Store Assessment
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `detail`, `questionnaire_id`) VALUES
              (:name, :detail, :questionnaire_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $assessment->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $assessment->detail, PDO::PARAM_STR);
      $stmt->bindParam(':questionnaire_id', $assessment->questionnaire_id, PDO::PARAM_INT);
      
      if($stmt->execute()) {
        $assessment->id = self::getConnection()->lastInsertId();
      } else {
        throw new Exception('Error to execute query! (Store Assessment)');
      }
      
      // Synchronizing Assessment-Task
      $sql = 'INSERT INTO `_assessment-task`
            (`assessment_id`, `task_id`) VALUES
            (:assessment_id	, :task_id)';

      $stmt = self::getConnection()->prepare($sql);
      foreach($assessment->tasks_id as $task_id) {
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->bindParam(':assessment_id', $assessment->id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query! (Synchronizing Assessment-Task)');
        } 
      }

      // Synchronizing Assessment-Evaluator
      $sql = 'INSERT INTO `_assessment-evaluator`
            (`assessment_id`, `evaluator_id`) VALUES
            (:assessment_id	, :evaluator_id)';

      $stmt = self::getConnection()->prepare($sql);
      foreach($assessment->evaluators_id as $evaluator_id) {
        $stmt->bindParam(':evaluator_id', $evaluator_id, PDO::PARAM_INT);
        $stmt->bindParam(':assessment_id', $assessment->id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query! (Synchronizing Assessment-Evaluator)');
        } 
      }

      return $assessment;

    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($assessment) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $assessment->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($assessment) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($assessment->element_1_id) && $assessment->element_1_id = NULL;
      empty($assessment->element_2_id) && $assessment->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $assessment->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $assessment->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $assessment->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $assessment->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $assessment->text, PDO::PARAM_STR);
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

  public static function tasks($id) {
    try {
      $sql = 'SELECT 
                task.id AS id, 
                task.name AS name, 
                task.detail AS detail
              FROM `_tasks` AS task
              JOIN `_assessment-task` AS assessment_task
                ON assessment_task.task_id = task.id
              WHERE assessment_task.assessment_id = :id';
     
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

  public static function evaluators($id) {
    try {
      $sql = 'SELECT 
                evaluator.id AS id, 
                evaluator.name AS name, 
                evaluator.email AS email
              FROM `_evaluators` AS evaluator
              JOIN `_assessment-evaluator` AS assessment_evaluator
                ON assessment_evaluator.evaluator_id = evaluator.id
              WHERE assessment_evaluator.assessment_id = :id';
    
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
}

?>