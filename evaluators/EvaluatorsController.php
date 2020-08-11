<?php 

require_once('../connection/Connect.php');

class EvaluatorsController extends Connect {
  private static $PK_table = 'id';
  private static $table_name = '_evaluators';

  public static function index() {
    try {
      $sql = 'SELECT `id`, `name`, `email`
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

  public static function search($query) {
    try {
      $sql = 'SELECT `id`, `name`, `email`
              FROM '.self::$table_name.'
              WHERE email LIKE :query
              OR name LIKE :query
              LIMIT :page, 20';
    
      $page = 0;

      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':query', $query, PDO::PARAM_STR);
      $stmt->bindParam(':page', $page, PDO::PARAM_INT);

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

  public static function store($evaluator) {
    try {
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `email`) VALUES
              (:name, :email)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $evaluator->name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $evaluator->email, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        $evaluator->id = self::getConnection()->lastInsertId();
        return $evaluator;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function syncWithProfile($evaluator) {
    try {

      $sql = 'INSERT INTO  `_evaluator-profile`
            (`evaluator_id`, `profile_id`) VALUES
            (:evaluator_id, :profile_id)';

      $stmt = self::getConnection()->prepare($sql);
      foreach($evaluator->profiles_ids as $profile_id) {
        $stmt->bindParam(':evaluator_id', $evaluator->id, PDO::PARAM_INT);
        $stmt->bindParam(':profile_id', $profile_id, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query! (Synchronizing Assessment-Task)');
        } 
      }

    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($id) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($evaluator) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($evaluator->element_1_id) && $evaluator->element_1_id = NULL;
      empty($evaluator->element_2_id) && $evaluator->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $evaluator->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $evaluator->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $evaluator->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $evaluator->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $evaluator->text, PDO::PARAM_STR);
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