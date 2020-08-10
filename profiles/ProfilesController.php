<?php 

require_once('../connection/Connect.php');

class ProfilesController extends Connect {
  // this table
  private static $PK_table = 'id';
  private static $table_name = '_profiles';

  // table pivot evaluator-profile
  private static $table_one_foreign = '_evaluator-profile';
  private static $table_one_foreign_pk = 'id';
  private static $table_one_foreign_id_1 = 'profile_id';
  private static $table_one_foreign_id_2 = 'evaluator_id';

  // users table
  private static $table_two_foreign = '_evaluators';
  private static $table_two_foreign_pk = 'id';  

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

  public static function store($profile) {
    try {
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `url`, designer_id) VALUES
              (:name, :url, :designer_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $profile->name, PDO::PARAM_STR);
      $stmt->bindParam(':url', $profile->url, PDO::PARAM_STR);
      $stmt->bindParam(':designer_id', $profile->designer_id, PDO::PARAM_INT);
      
      if($stmt->execute()) {
        return self::getConnection()->lastInsertId();
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($profile) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $profile->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($profile) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($profile->element_1_id) && $profile->element_1_id = NULL;
      empty($profile->element_2_id) && $profile->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $profile->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $profile->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $profile->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $profile->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $profile->text, PDO::PARAM_STR);
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

  public static function evaluators($profile) {
    try {
      $sql = 'SELECT 
                evaluators.id AS id, 
                evaluators.name AS name, 
                evaluators.email AS email
              FROM '.self::$table_two_foreign.' AS evaluators
              JOIN `'.self::$table_one_foreign.'` evaluator_profile
                ON evaluator_profile.'.self::$table_one_foreign_id_2.' = evaluators.id
              WHERE evaluator_profile.'.self::$table_one_foreign_id_1.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $profile->id, PDO::PARAM_INT);

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