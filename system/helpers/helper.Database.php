<?php
/**
* Database Helper
*
* UserCandy PHP Framework
* @author David (DaVaR) Sargent <davar@usercandy.com>
* @version UC 2.0.0.0
*/

namespace Helpers;

use Core\ErrorLogger;

class Database extends \PDO {

    /**
     * @var array Array of saved databases for reusing
     */
    protected static $instances = array();
    /**
     * Static method get
     *
     * @param  array $group
     * @return \Helpers\Database
     */
    public static function get($group = false)
    {
        // Determining if exists or it's not empty, then use default group defined in config
        $group = !$group ? array (
            'type' => DB_TYPE,
            'host' => DB_HOST,
            'name' => DB_NAME,
            'user' => DB_USER,
            'pass' => DB_PASS
        ) : $group;
        // Group information
        $type = $group['type'];
        $host = $group['host'];
        $name = $group['name'];
        $user = $group['user'];
        $pass = $group['pass'];
        // ID for database based on the group information
        $id = "$type.$host.$name.$user.$pass";
        // Checking if the same
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }
        try {
            // I've run into problem where
            // SET NAMES "UTF8" not working on some hostings.
            // Specifiying charset in DSN fixes the charset problem perfectly!
            $instance = new Database("$type:host=$host;dbname=$name;charset=utf8", $user, $pass);
            $instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // Setting Database into $instances to avoid duplication
            self::$instances[$id] = $instance;
            return $instance;
        } catch (\PDOException $e) {
            //in the event of an error record the error to Error Log File
            ErrorLogger::newMessage($e);
            ErrorLogger::customErrorMsg();
        }
    }
    /**
     * run raw sql queries
     * @param  string $sql sql command
     * @return return query
     */
    public function raw($sql)
    {
        return $this->query($sql);
    }
    /**
     * method for selecting records from a database
     * @param  string $sql       sql query
     * @param  array  $array     named params
     * @param  object $fetchMode
     * @param  string $class     class name
     * @return array            returns an array of records
     */
    public function select($sql, $array = array(), $fetchMode = \PDO::FETCH_OBJ, $class = '')
    {
        $stmt = $this->prepare($sql);
        foreach ($array as $key => $value) {
            if(strpos($key,":") === false) {
                $key = ":".$key;
            }
            if (is_int($value)) {
                $stmt->bindValue("$key", $value, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue("$key", $value);
            }
        }
        $stmt->execute();
        if ($fetchMode === \PDO::FETCH_CLASS) {
            return $stmt->fetchAll($fetchMode, $class);
        } else {
            return $stmt->fetchAll($fetchMode);
        }
    }
    /**
     * method for selecting records from a database
     * @param  string $sql       sql query
     * @param  array  $array     named params
     * @param  object $fetchMode
     * @param  string $class     class name
     * @return int            returns row count
     */
    public function selectCount($table, $where=array())
    {
        if(!empty($where)){
            ksort($where);
            $whereDetails = null;
            $i = 0;
            foreach ($where as $key => $value) {
                if ($i == 0) {
                    $whereDetails .= "$key = :$key";
                } else {
                    $whereDetails .= " AND $key = :$key";
                }
                $i++;
            }
            $whereDetails = ltrim($whereDetails, ' AND ');
            $stmt = $this->prepare("SELECT COUNT(*) FROM $table WHERE $whereDetails");
        }else{
            $stmt = $this->prepare("SELECT COUNT(*) FROM $table");
        }
        
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    /**
     * insert method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     */
    public function insert($table, $data)
    {
        ksort($data);
        $fieldNames = implode(',', array_keys($data));
        $fieldValues = ':'.implode(', :', array_keys($data));
        $stmt = $this->prepare("INSERT INTO $table ($fieldNames) VALUES ($fieldValues)");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $this->lastInsertId();
    }
    /**
     * update method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     * @param  array $where array of columns and values
     */
    public function update($table, $data, $where)
    {
        ksort($data);
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Delete method
     *
     * @param  string $table table name
     * @param  array $where array of columns and values
     * @param  integer   $limit limit number of records
     */
    public function delete($table, $where, $limit = 1)
    {
        ksort($where);
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        //if limit is a number use a limit on the query
        if (is_numeric($limit)) {
            $uselimit = "LIMIT $limit";
        }
        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails $uselimit");
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Delete method
     *
     * @param  string $table table name
     * @param  array $where array of columns and values
     * @param  integer   $limit limit number of records
     */
    public function deleteAll($table, $where)
    {
        ksort($where);
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :$key";
            } else {
                $whereDetails .= " AND $key = :$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $stmt = $this->prepare("DELETE FROM $table WHERE $whereDetails");
        foreach ($where as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * update where not method
     * @param  string $table table name
     * @param  array $data  array of columns and values
     * @param  array $where array of columns and values
     */
    public function updateWhereNot($table, $data, $where, $whereNot)
    {
        ksort($data);
        $fieldDetails = null;
        foreach ($data as $key => $value) {
            $fieldDetails .= "$key = :field_$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        $whereDetails = null;
        $i = 0;
        foreach ($where as $key => $value) {
            if ($i == 0) {
                $whereDetails .= "$key = :where_$key";
            } else {
                $whereDetails .= " AND $key = :where_$key";
            }
            $i++;
        }
        $whereDetails = ltrim($whereDetails, ' AND ');
        $whereNotDetails = null;
        $i = 0;
        foreach ($whereNot as $key => $value) {
            if ($i == 0) {
                $whereNotDetails .= "$key = :wherenot_$key";
            } else {
                $whereNotDetails .= " AND $key = :wherenot_$key";
            }
            $i++;
        }
        $whereNotDetails = ltrim($whereNotDetails, ' AND NOT ');
        $stmt = $this->prepare("UPDATE $table SET $fieldDetails WHERE $whereDetails AND NOT $whereNotDetails");
        foreach ($data as $key => $value) {
            $stmt->bindValue(":field_$key", $value);
        }
        foreach ($where as $key => $value) {
            $stmt->bindValue(":where_$key", $value);
        }
        foreach ($whereNot as $key => $value) {
            $stmt->bindValue(":wherenot_$key", $value);
        }
        $stmt->execute();
        return $stmt->rowCount();
    }

    /**
     * Open Delete method
     *
     * @param  string $sql query
     */
    public function delete_open($sql)
    {
        $stmt = $this->prepare("DELETE FROM $sql");
        $stmt->execute();
        return $stmt->rowCount();
    }
    /**
     * truncate table
     * @param  string $table table name
     */
    public function truncate($table)
    {
        return $this->exec("TRUNCATE TABLE $table");
    }
    /**
     * run update exec for database updates
     * @param  string $sql sql command
     * @return return query
     */
    public function upgrade($sql)
    {
        return $this->exec($sql);
    }

}
