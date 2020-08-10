<?php 

require_once('../connection/Connect.php');

class WebsitesController extends Connect {
  // this table
  private static $PK_table = 'id';
  private static $table_name = '_websites';

  // pages table
  private static $table_one_foreign = '_pages';
  private static $table_one_foreign_pk = 'id';
  private static $table_one_foreign_id_1 = 'website_id';

  // tasks table
  private static $table_two_foreign = '_tasks';
  private static $table_two_foreign_pk = 'id';

  // table pivot tasks-pages
  private static $table_three_foreign = '_page-task-designer';
  private static $table_three_foreign_pk = 'id';
  private static $table_three_foreign_id_1 = 'task_id';
  private static $table_three_foreign_id_2 = 'page_id';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `name`, `url`
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

  public static function store($website) {
    try {
      $sql = 'INSERT INTO '.self::$table_name.'
            (`name`, `url`, `designer_id`, `manager_id`) VALUES
            (:name, :url, :designer_id, :manager_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $website->name, PDO::PARAM_STR);
      $stmt->bindParam(':url', $website->url, PDO::PARAM_STR);
      $stmt->bindParam(':designer_id', $website->designer_id, PDO::PARAM_INT);
      $stmt->bindParam(':manager_id', $website->manager_id, PDO::PARAM_INT);
      
      if($stmt->execute()) {
        $website->id = self::getConnection()->lastInsertId();
        return $website;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($website) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $website->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function pages($website_id) {
    try {
      $sql = 'SELECT `id`, `name`, `url`
              FROM '.self::$table_one_foreign.'
              WHERE '.self::$table_one_foreign_id_1.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $website_id, PDO::PARAM_INT);

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
  
  public static function tasks($website_id) {
    try {
      $sql = 'SELECT DISTINCT
                task.id AS task_id,
                task.name AS task_name,
                task.detail AS task_detail,
                assessment.id AS assessment_id,
                assessment.completed_at AS assessment_completed_at
              FROM `_tasks` AS task 
              JOIN `_page-task-designer` AS page_task 
                ON page_task.task_id = task.id 
              JOIN `_pages` AS page 
                ON page_task.page_id = page.id
              LEFT JOIN `_assessment-task` AS assessment_task
                ON assessment_task.task_id = task.id
              LEFT JOIN `_assessments` AS assessment
                ON assessment.id = assessment_task.assessment_id
              WHERE page.website_id = :website_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':website_id', $website_id, PDO::PARAM_INT);
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

  public static function update($website) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($website->element_1_id) && $website->element_1_id = NULL;
      empty($website->element_2_id) && $website->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $website->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $website->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $website->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $website->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $website->text, PDO::PARAM_STR);
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