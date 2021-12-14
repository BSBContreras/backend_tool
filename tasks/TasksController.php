<?php 

require_once('../connection/Connect.php');

class TasksController extends Connect {
  // this table
  private static $PK_table = 'id';
  private static $table_name = '_tasks';
  
  // table pivot tasks-pages
  private static $table_one_foreign = '_page-task-designer';
  private static $table_one_foreign_pk = 'id';
  private static $table_one_foreign_id_1 = 'task_id';
  private static $table_one_foreign_id_2 = 'page_id';

  // pages table
  private static $table_two_foreign = '_pages';
  private static $table_two_foreign_pk = 'id';
  private static $table_two_foreign_id_1 = 'website_id';

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

  public static function store($task) {
    try {
      $sql = 'INSERT INTO '.self::$table_name.'
            (`name`, `detail`) VALUES
            (:name, :detail)';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':name', $task->name, PDO::PARAM_STR);
      $stmt->bindParam(':detail', $task->detail, PDO::PARAM_STR);
      
      if($stmt->execute()) {
        return self::getConnection()->lastInsertId();
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function sync($task) {
    try {
      // Detaching pages
      $sql = 'DELETE FROM `'.self::$table_one_foreign.'`
              WHERE '.self::$table_one_foreign_id_1.' = :task_id';

      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':task_id', $task->id, PDO::PARAM_INT);

      if(!$stmt->execute()) {
        throw new Exception('Error to execute query!');
      } 

      // Attaching pages
      $sql = 'INSERT INTO `'.self::$table_one_foreign.'`
              (`page_id`, `task_id`, `sort_key`) VALUES
              (:page_id, :task_id, :sort_key)';

      $stmt = self::getConnection()->prepare($sql);
      foreach($task->pages as $key => $page_id) {
        $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
        $stmt->bindParam(':task_id', $task->id, PDO::PARAM_INT);
        $stmt->bindParam(':sort_key', $key, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query!');
        } 
      }

      return $task;

      self::updateTimeStamp($task->id);
    
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function syncUser($task) {
    try {
      // Detaching pages
      $sql = 'DELETE FROM `_page-task-evaluator`
              WHERE `task_id` = :task_id
              AND `evaluator_id` = :evaluator_id';

      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':task_id', $task->task_id, PDO::PARAM_INT);
      $stmt->bindParam(':evaluator_id', $task->evaluator_id, PDO::PARAM_INT);

      if(!$stmt->execute()) {
        throw new Exception('Error to execute query!');
      } 

      // Attaching pages
      $sql = 'INSERT INTO `_page-task-evaluator`
              (`page_id`, `task_id`, `evaluator_id`, `sort_key`) VALUES
              (:page_id, :task_id, :evaluator_id, :sort_key)';

      $stmt = self::getConnection()->prepare($sql);
      foreach($task->pages as $key => $page_id) {
        $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
        $stmt->bindParam(':task_id', $task->task_id, PDO::PARAM_INT);
        $stmt->bindParam(':evaluator_id', $task->evaluator_id, PDO::PARAM_INT);
        $stmt->bindParam(':sort_key', $key, PDO::PARAM_INT);

        if(!$stmt->execute()) {
          throw new Exception('Error to execute query!');
        } 
      }

      return $task;

    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function delete($task) {
    try {
      $sql = 'DELETE FROM '.self::$table_name.'
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $task->id, PDO::PARAM_INT);

      if($stmt->execute()) {
        return null;
      } else {
        throw new Exception('Error to execute query!');
      }
    } catch(PDOException $e) {
      throw new Exception($e->getMessage().' -> '.$sql);
    }
  }

  public static function pages($task_id) {
    try {
      $sql = 'SELECT *
              FROM '.self::$table_two_foreign.' AS page
              JOIN `'.self::$table_one_foreign.'` page_task
                ON page_task.'.self::$table_one_foreign_id_2.' = page.id
              WHERE page_task.'.self::$table_one_foreign_id_1.' = :id
              ORDER BY page_task.sort_key';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $task_id, PDO::PARAM_INT);

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

  public static function pagesUser($task_id, $evaluator_id) {
    try {
      $sql = 'SELECT *
              FROM `_pages` AS page
              JOIN `_page-task-evaluator` AS page_task
                ON page_task.page_id = page.id
              WHERE page_task.task_id = :task_id
              AND page_task.evaluator_id = :evaluator_id
              ORDER BY page_task.sort_key';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
      $stmt->bindParam(':evaluator_id', $evaluator_id, PDO::PARAM_INT);

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

  public static function update($task) {
    try {
      $date = date("Y-m-d H:i:s");

      empty($task->element_1_id) && $task->element_1_id = NULL;
      empty($task->element_2_id) && $task->element_2_id = NULL;

      $sql = 'UPDATE '.self::$table_name.' SET
              `criterion_id` = :criterion_id,
              `element_1_id` = :element_1_id,
              `element_2_id` = :element_2_id,
              `text` = :text,
              `updated_at` = :date
              WHERE '.self::$PK_table.' = :id';
    
      $stmt = self::getConnection()->prepare($sql);
      $stmt->bindParam(':id', $task->id, PDO::PARAM_INT);
      $stmt->bindParam(':criterion_id', $task->criterion_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_1_id', $task->element_1_id, PDO::PARAM_INT);
      $stmt->bindParam(':element_2_id', $task->element_2_id, PDO::PARAM_INT);
      $stmt->bindParam(':text', $task->text, PDO::PARAM_STR);
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
      date_default_timezone_set('America/Sao_Paulo');
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