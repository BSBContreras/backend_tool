<?php 

require_once('../connection/Connect.php');

class PagesController extends Connect {
  private static $PK_table = 'id';
  private static $table_name = '_pages';

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

  public static function store($page) {
    try {
      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `url`, `website_id`, `element_1_id`, `element_2_id`, `element_3_id`, `element_4_id`, `element_5_id`) VALUES
              (:name, :url, :website_id, :element_1_id, :element_2_id, :element_3_id, :element_4_id, :element_5_id)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $page->name, PDO::PARAM_STR);
      $stmt->bindParam(':url', $page->url, PDO::PARAM_STR);
      $stmt->bindParam(':website_id', $page->website_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $page->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $page->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_3_id', $page->element_3_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_4_id', $page->element_4_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_5_id', $page->element_5_id, PDO::PARAM_INT);
      
      if($stmt->execute()) {
        return self::getConnection()->lastInsertId();
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($page) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $page->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($page) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($page->element_1_id) && $page->element_1_id = NULL;
      empty($page->element_2_id) && $page->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $page->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $page->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $page->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $page->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $page->text, PDO::PARAM_STR);
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