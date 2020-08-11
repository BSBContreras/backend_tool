<?php 

require_once('../connection/Connect.php');

class CriteriaController extends Connect {
  private static $table_name = '_criteria';
  private static $table_one_foreign = '_questions';
  private static $table_one_foreign_id = 'criterion_id';
  private static $PK_table = 'id';

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

      $sql = 'INSERT INTO '.self::$table_name.'
              (`name`, `detail`) VALUES
              (:name, :detail)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $criterion->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $criterion->detail, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        $criterion->id = self::getConnection()->lastInsertId();
        return $criterion;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($criterion_id) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :criterion_id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':criterion_id', $criterion_id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function update($criterion) {
    try {
      $sql = 'UPDATE '.self::$table_name.' SET
              `name` = :name,
              `detail` = :detail
              `updated_at` = NULL
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $criterion->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $criterion->detail, PDO::PARAM_STR);
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

  public static function questions($criterion_id) {
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
      $stmt->bindParam(':criterion_id', $criterion_id, PDO::PARAM_INT);

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