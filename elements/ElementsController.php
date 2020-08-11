<?php 

require_once('../connection/Connect.php');

class ElementsController extends Connect {
  private static $PK_table = 'id';
  private static $table_name = '_elements';
  private static $table_one_foreign = '_questions';
  private static $table_one_foreign_id = 'element_1_id';
  private static $table_two_foreign_id = 'element_2_id';

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

  public static function store($element) {
    try {

      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `detail`) VALUES
              (:name, :detail)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $element->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $element->detail, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        $element->id = self::getConnection()->lastInsertId();
        return $element;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($element_id) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $element_id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($element) {
    try {

      $sql = 'UPDATE '.self::$table_name.' SET
              `name` = :name,
              `detail` = :detail,
              `updated_at` = NULL
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $element->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $element->detail, PDO::PARAM_STR);
      $stmt->bindParam(':id', $element->id, PDO::PARAM_INT);

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

  public static function questions($element_id) {
    try {
      $sql = 'SELECT `id`, `text` 
              FROM '.self::$table_one_foreign.'
              WHERE '.self::$table_one_foreign_id.' = :id
              OR '.self::$table_two_foreign_id.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $element_id, PDO::PARAM_INT);

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