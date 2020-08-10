<?php 

require_once('../connection/Connect.php');

class ManagersController extends Connect {
  private static $table_name = '_managers';
  private static $PK_table = 'id';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `name` 
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

  public static function showByEmail($email) {
    try {
      $sql = 'SELECT *
              FROM '.self::$table_name.'
              WHERE email = :email';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      
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

  public static function store($manager) {
    try {
      $password_crypt = crypt($manager->password);
      
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `email`, `password`) VALUES
              (:name, :email, :password)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $manager->name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $manager->email, PDO::PARAM_STR);     
      $stmt->bindParam(':password', $password_crypt, PDO::PARAM_STR);

      if($stmt->execute()) {
        $manager->id = self::getConnection()->lastInsertId();
        return $manager;
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

  public static function questionnaires($manager_id) {
    try {
      $sql = 'SELECT 
              questionnaire.`id` AS questionnaire_id, 
              questionnaire.`name` AS questionnaire_name,
              questionnaire.`detail` AS questionnaire_detail, 
              questionnaire.`manager_id` AS questionnaire_manager_id,
              assessment.`completed_at` AS assessment_completed_at,
              assessment.`id` AS assessment_id
              FROM `_questionnaires` as questionnaire
              LEFT OUTER JOIN `_assessments` as assessment
              ON questionnaire.id = assessment.questionnaire_id
              WHERE questionnaire.manager_id = :manager_id
              ORDER BY questionnaire.id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':manager_id', $manager_id, PDO::PARAM_INT);

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

  public static function websites($manager_id) {
    try {
      $sql = 'SELECT 
              website.`id` AS id, 
              website.`name` AS name,
              website.`url` AS url
              FROM `_websites` as website
              WHERE website.manager_id = :manager_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':manager_id', $manager_id, PDO::PARAM_INT);

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

  public static function assessments($manager_id) {
    try {

      $sql = 'SELECT 
              assessment.`id`, 
              assessment.`name`, 
              assessment.`detail`, 
              assessment.`questionnaire_id`,
              questionnaire.`manager_id`
              FROM `_assessments` AS assessment
              JOIN `_questionnaires` AS questionnaire
              ON questionnaire.id = assessment.questionnaire_id
              WHERE questionnaire.manager_id = :manager_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':manager_id', $manager_id, PDO::PARAM_INT);

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

  public static function update($criterion) {
    try {
      $date = date("Y-m-d H:i:s");

      $sql = 'UPDATE '.self::$table_name.' SET
              `name` = :name,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $criterion->name, PDO::PARAM_STR);
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':id', $criterion->id, PDO::PARAM_INT);

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