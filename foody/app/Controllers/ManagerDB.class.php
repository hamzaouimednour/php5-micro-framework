<?php
/**
 * @author Hamzaoui Med Nour <Hamzaouimohamednour@gmail.com>
 * @version 1.0.0
 * @license
 * @copyright Copyright (c) 2019
 * @throws PDOException
 */


/** 
 * user another variable if you create class daynamic or declared before fetching
 * @var object $user = $user->getAll() or $user = (new Class)->getAll()
 */
class ManagerDB extends dbConnection{

    private     $errorMessage   = NULL; // the Message error .
    private     $isNull         = NULL; // the Message error .
    private     $duplicate_key  = NULL; // the Message error .
    private     $where          = NULL;
    private     $inner_join     = NULL;
    private     $selections     = NULL;
    private     $distinct       = FALSE;
    private     $orderBy        = NULL;
    private     $cast           = NULL;
    private     $limit          = NULL;

    /**
     * __construct : extend method openConnection() from dbConnection to start connection.
     */
    public function __construct(){
        $this->db = parent::openConnection();
    }
    /**
     * setTraceException : set error infos into the vars.
     *
     * @param string $errorInfo
     * @return void
     */
    private function setTraceException($errorInfo){
        $this->errorMessage = $errorInfo; // [errorCode, errorMessage].
        return FALSE;
    }

    private function bindRows(array $rows){
        $bindRows = $rows; //we dont want to override array fields.
        array_walk($bindRows, function(&$item) { $item .= ' = :'.$item; });
        return implode(', ', $bindRows);
    }
    private function bindRowsValues(array $rows){
        $bindRows = $rows; //we dont want to override array fields.
        array_walk($bindRows, function(&$item) { $item .= ' = VALUES('.$item.')'; });
        return implode(', ', $bindRows);
    }
    /**
     * this function check the empty properties and remove them, that's mean no update for them.
     * @method issetRows
     * @param  object        $object        [description]
     * @return array         $issetRows     ['definedRows','bindRows']
     * @access private
     */
    private function issetRows($object, $popPK = FALSE){
        // Handler::check_object($object);
        $rows = $object::__getFields();
        (!$popPK) ? NULL : $rows = array_diff($rows, [$object::primaryKey]);
        foreach($rows as $row){
            $method = $object->{'get' . Handler::getMethod($row)}();
            (Handler::is_empty($method)) ? $rows = array_diff($rows, [$row]) : NULL;
        }
        $issetRows['definedRows'] = $rows;
        $issetRows['insertRows']  = implode(', ',preg_filter('/^/', ':', $rows));
        $issetRows['bindRows']    = $this->bindRows($rows);
        return $issetRows;
    }
    /**
     * Function Insert an Object into DB receives as parameters :
     * @method insert
     * @param  object $object       [description]
     * @param  string $tableName     [description]
     * @return boolean
     */
    public function insert($object, $tableName, $lastId = NULL){
        try{
            $rows = $this->issetRows($object);
            $fields = implode(', ',$rows['definedRows']);
            $InsertStatment = "INSERT INTO `$tableName` ($fields) VALUES (".$rows['insertRows'].")";
            $query = $this->db->prepare($InsertStatment);
            
            // PDOStatement::bindValue : Bind Properties (boolean)
            foreach ($rows['definedRows'] as $property){
                $query->bindValue(
                    ":$property", call_user_func( array($object, 'get'.Handler::getMethod($property)) )
                );
            }
            // PDOStatement::execute : Executes a prepared PDOStatement (boolean).
            parent::endConnection();
            return $query->execute() ? (is_null($lastId) ? TRUE : $this->db->lastInsertId()) : FALSE; 
            
        }catch(PDOException $error){
            // Exception::getMessage : Handling throw a PDOException.
            // return $query->errorCode();
            return $this->setTraceException($error->getMessage());
        }
    }
    public function multiInsert($object, $tableName, $lastId = NULL){
        try{
            $rows = $this->issetRows($object);
            $fields = implode(', ', $rows['definedRows']);
            // PDOStatement::bindValue : Bind Properties (boolean)
            // $sizeArray = array();
            foreach ($rows['definedRows'] as $property){
                $getMethod = $object->{'get' . Handler::getMethod($property)}();
                $sizeArray[] = count($getMethod);
            }
            $rowIndex = 0;
            foreach ($rows['definedRows'] as $property){
                $getMethod = $object->{'get' . Handler::getMethod($property)}();
                for ($i=0; $i < max($sizeArray) ; $i++) {
                    $value = (is_array($getMethod)) ? $getMethod[$i] : $getMethod;
                    $insertArray[$i][$rowIndex] = "'". $value . "'";
                    $rowIndex++;
                }
            }
            
            $insertValues = join(', ', array_map(function (array $values) {  return '(' . implode(', ' , $values) . ')'; }, $insertArray));            
            $InsertStatment = "INSERT INTO `$tableName` ($fields) VALUES $insertValues";
            $InsertStatment .= is_null($this->duplicate_key) ? NULL : ' ON DUPLICATE KEY UPDATE ' . $this->bindRowsValues($rows['definedRows']);
            $query = $this->db->prepare($InsertStatment);
            // PDOStatement::execute : Executes a prepared PDOStatement (boolean).
            parent::endConnection();
            return $query->execute() ? (is_null($lastId) ? TRUE : $this->db->lastInsertId()) : FALSE; 
            
        }catch(PDOException $error){
            // Exception::getMessage : Handling throw a PDOException.
            // return $query->errorCode();
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * @method set_conditions : convert an array of conditions to string.
     * @param  array  $conditionArray [array($field => array(operator => value), $field2 => ...)]
     * @return string $condition
     * @access private
     * operators : > >= < <= = <> BETWEEN LIKE IN array('uid', ["=", $this->getUid()])
     */
    private function set_conditions(array $conditionArray, $apostrophe = NULL){
        $condition = "";
        if(!empty($conditionArray)){
            $condition = " WHERE " ;
            if(is_null($apostrophe)){
                foreach ($conditionArray as $field => $val){
                    $condition .= $field.' '.$val[0]." '".$val[1]."' AND ";
                }
            }else{
                foreach ($conditionArray as $field => $val){
                    $condition .= $field.' '.$val[0]." ".$val[1]." AND ";
                }
            }
            $condition = substr($condition,0,strlen($condition)-5); // remove the last 'and '.
        }
        return $condition;
    }
    /**
     * [update description]
     * @method update
     * @param  object  $object       [description]
     * @param  string  $tableName     [description]
     * @param  array   $conditions    [description]
     * @return boolean
     */
    public function update($object, $tableName, array $conditions){
        try{
            $condition = $this->set_conditions($conditions);
            $rows = $this->issetRows($object, TRUE);//remove empty fields and primary key field.
            // $rows = array_diff($rows , array_keys($conditions)); //remove condtion fields from fields to update.
            $bindRows = $this->bindRows(array_diff($rows['definedRows'] , array_keys($conditions))); //remove condtion fields from fields to update by BIND FORMAT.
            $updateStatment = "UPDATE `$tableName` SET $bindRows $condition";
            $query = $this->db->prepare($updateStatment);
            // PDOStatement::bindValue : Bind Properties (boolean)

            $definedRows = array_diff($rows['definedRows'] , array_keys($conditions)); //remove condtions fields from fields to bind
            foreach ($definedRows as $property){
                $query->bindValue(
                    ":$property", $object->{'get' . Handler::getMethod($property)}()
                );
            }
            // PDOStatement::execute : Executes a prepared PDOStatement (boolean).
            parent::endConnection();
            return $query->execute() ? TRUE : FALSE;
        }catch(PDOException $error){
            // Exception::getMessage : Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    public function updateRows($object, $tableName, array $rows){
        try{
            $bindRows = $this->bindRows($rows); //remove empty fields and primary key field.
            $updateStatment = "UPDATE `$tableName` SET $bindRows ";
            $updateStatment .= is_null($this->where) ? NULL : $this->where;
            $query = $this->db->prepare($updateStatment);
            // PDOStatement::bindValue : Bind Properties (boolean)
            foreach ($rows as $property){
                $query->bindValue(
                    ":$property", $object->{'get' . Handler::getMethod($property)}()
                );
            }
            // PDOStatement::execute : Executes a prepared PDOStatement (boolean).
            parent::endConnection();
            return $query->execute() ? TRUE : FALSE;
        }catch(PDOException $error){
            // Exception::getMessage : Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * @method getAttributes
     * @param  string $tableName [description]
     * @return array|boolean
     * PDO::FETCH_COLUMN: Returns the indicated 0-indexed column.
     * NOTE: $query->fetchAll(PDO::FETCH_COLUMN, 1) for the second column.
     */
    public function getAttributes($tableName){
        try{
            $descStmt = "DESCRIBE $tableName";
            $query = $this->db->query($descStmt);
            // Fetch all of the values of the first column.
            parent::endConnection();
            return !empty($query) ? $query->fetchAll(PDO::FETCH_COLUMN) : FALSE;
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * [fetch description]
     * @method fetch
     * @param  string $className [description]
     * @param  string $tableName [description]
     * @return array|boolean
     * PDOStatement::fetchAll() returns an array containing all of the remaining rows in the result set.
     * The array represents each row as either an array of column values or an  object with  properties
     * corresponding to each column name. An empty array is returned if there are zero results to fetch,
     * or FALSE on failure. i.e : $a->setDescOrder()->setCast("id", "int")->fetch("test", "id");
     */
    public function fetch($className, $tableName, $specialCols = NULL){
        try{
            $selections = is_null($this->selections) ? '*' : $tableName . '.*' ;
            if(!is_null($specialCols)){
                $selections = implode ( ', ' , $specialCols );
            }
            $sqlStatment = "SELECT $selections FROM `$tableName` ";
            $sqlStatment .= is_null($this->inner_join) ? NULL : $this->inner_join;
            $sqlStatment .= is_null($this->where) ? NULL : $this->where;
            $sqlStatment .= is_null($this->orderBy) ? NULL : $this->orderBy;
            $sqlStatment .= is_null($this->limit) ? NULL : $this->limit;
            $query = $this->db->query($sqlStatment);
            if(!empty($query) and class_exists($className)){
                /* PDO::FETCH_CLASS: Returns instances of the specified class,mapping the columns of each row to named properties in the class. */
                $fetchedData = $query->fetchAll(PDO::FETCH_CLASS, $className);
                parent::endConnection();
                return $fetchedData;
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * @method delete : remove rows from the given table name by one or many conditions.
     * @param  string $tableName   [description]
     * @return boolean
     * PDO::exec() executes an SQL statement in a single function call, returning the number of rows affected by the statement.
     */
    public function delete($tableName){
        try{
            $removeStmt = "DELETE FROM `$tableName` ";
            $removeStmt .= is_null($this->where) ? NULL : $this->where;
            $removedRows = $this->db->exec($removeStmt);
            parent::endConnection();
            return ($removedRows > 0);
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * [truncate table : unsing the reserved keyword 'TRUNCATE' in SQL that's mean remove all rows of the given table.]
     * @method truncate
     * @param  string   $tableName  [description]
     * @return boolean              [description]
     */
    public function truncate($tableName){
        try{
            $removeStmt = "TRUNCATE TABLE `$tableName`";
            $affectedRows = $this->db->exec($removeStmt);
            parent::endConnection();
            return ($affectedRows > 0) ? TRUE : FALSE;
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * [reset the AUTO_INCREMENT of the given table]
     * @method resetai
     * @param  string  $tableName [description]
     * @return unknown
     * PDO::exec() executes an SQL statement in a single function call, returning the number of rows
     * affected by the statement.
     */
    public function resetAutoIncrement($tableName){
		$this->db->exec("ALTER TABLE `$tableName` AUTO_INCREMENT = 1");
        parent::endConnection();
    }
    /**
     * Check existence of an element in DB.
     * @param string $tableName
     * @return boolean
     */
    public function checkExist($tableName){
        try{
            $stmt = "SELECT * FROM `$tableName` ";
            $stmt .= is_null($this->where) ? NULL : $this->where;
            $query = $this->db->query($stmt);
            parent::endConnection();
            if(!empty($query)){
                return ($query->rowCount()>0) ? TRUE : FALSE;
            }
            return FALSE;
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
	}
    /**
     * fetchObject : return Object of given class.
     *
     * @param string $className
     * @param string $tableName
     * @return object
     */
    public function fetchObject($className, $tableName){
        try{
            $sqlStatment = "SELECT * FROM `$tableName` ";
            $sqlStatment .= is_null($this->where) ? NULL : $this->where;
            $sqlStatment .= is_null($this->orderBy) ? NULL : $this->orderBy;
            $sqlStatment .= is_null($this->limit) ? NULL : $this->limit;
            $query = $this->db->query($sqlStatment);
            // If $query empty that's mean an error occurred.
            if(!empty($query) and class_exists($className)){
                /* PDO::FETCH_CLASS: Returns instances of the specified class,mapping the columns of each row to named properties in the class. */
                $fetchedData = $query->fetchObject($className);
                parent::endConnection();
                return $fetchedData; //array of class
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * [get data from the given table but ignore the deplicated rows]
     * @method fetchDistinct
     * @param  string        $className [description]
     * @param  string        $tableName [description]
     * @param  string        $row       [description]
     * @return array|boolean
     */
    public function fetchDistinct($className, $tableName, $row){
        try{
            $sqlStatment = "SELECT DISTINCT(`$row`) FROM `$tableName` ";
            $sqlStatment .= is_null($this->where) ? NULL : $this->where;
            $sqlStatment .= is_null($this->orderBy) ? NULL : $this->orderBy;
            $sqlStatment .= is_null($this->limit) ? NULL : $this->limit;
            $query = $this->db->query($sqlStatment);
            // If $query empty that's mean an error occurred.
            if(!empty($query) and class_exists($className)){
                /* PDO::FETCH_CLASS: Returns instances of the specified class,mapping the columns of each row to named properties in the class. */
                $fetchedData = $query->fetchAll(PDO::FETCH_CLASS, $className);
                parent::endConnection();
                return $fetchedData;
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * [fetchCount description]
     * @method fetchCount
     * @param  string     $tableName  [description]
     * @param  string     $row        [description]
     * @param  boolean    $distinct   [description]
     * @return int|boolean                 [description]
     * PDO::FETCH_NUM: returns an array indexed by column number starting at column 0  .
     * WARNING: $distinct should equal false when conditions array defined.
     * @example fetchCount('Table', 'Column', FALSE, array()) , fetchCount('Table', 'Column') ...
     */
    public function count($tableName, $row){
        try{
            $countStr = (!$this->distinct) ? "COUNT(`$row`)" : "COUNT(DISTINCT(`$row`))";
            $countStmt = "SELECT $countStr FROM `$tableName` ";
            $countStmt .= is_null($this->where) ? NULL : $this->where;
            $query = $this->db->query($countStmt);
            // If $query empty that's mean an error occurred.
            if(!empty($query)){
                $fetchedData = $query->fetchColumn();
                parent::endConnection();
                return $fetchedData;
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    public function getLastId($tableName, $id){
        try{
            $sqlStmt = "SELECT MAX($id) FROM `$tableName` ";
            $sqlStmt .= is_null($this->where) ? NULL : $this->where;
            $query = $this->db->query($sqlStmt);
            if(!empty($query)){
                $fetchedData = $query->fetchColumn();
                parent::endConnection();
                return $fetchedData;
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    public function getRoundAvg($tableName, $column){
        try{
            $sqlStmt = "SELECT ROUND(AVG($column), 1) FROM `$tableName` ";
            $sqlStmt .= is_null($this->where) ? NULL : $this->where;
            $query = $this->db->query($sqlStmt);
            if(!empty($query)){
                $fetchedData = $query->fetchColumn();
                parent::endConnection();
                return empty($fetchedData) ? '0.0' : $fetchedData;
            }
            return FALSE; // Returns False : $query contain Error.
        }catch(PDOException $error){
            // Exception::getMessage Handling throw a PDOException.
            return $this->setTraceException($error->getMessage());
        }
    }
    /**
     * getExceptionMessage : return the error's message.
     * @return string
     */
    public function getExceptionMessage(){
        return $this->errorMessage;
    }
    public function orderBy($field = NULL, $order = NULL){
        $this->orderBy = (!is_null($this->cast) && is_null($field)) ? " ORDER BY ".$this->cast : " ORDER BY $field";
        $this->orderBy .= is_null($order) ? " ASC " : " DESC ";
        return $this;
    }
    public function distinct(){
        $this->distinct = TRUE;
        return $this;
    }
    //i.e : $db->setCast("id", "Int")->setOrderBy()->fetch(...)
    public function cast($field, $type){
        $this->cast = "CAST($field AS $type)";
        return $this;
    }
    public function limit($limit, $offset = NULL){
        $this->limit = is_null($offset) ? " LIMIT $limit" : " LIMIT $offset, $limit" ;
        return $this;
    }
    public function where(array $conditions, $apostrophe = NULL){
        $this->where = $this->set_conditions($conditions, $apostrophe);
        return $this;
    }
    public function duplicateKey(){
        $this->duplicate_key = true;
        return $this;
    }
    public function innerJoin($joinTable, array $conditions, $apostrophe = NULL, $selectJoin = NULL){
        $this->selections = is_null($selectJoin) ? NULL : TRUE;
        $this->inner_join = " INNER JOIN $joinTable ON " . str_replace('WHERE', '', $this->set_conditions($conditions, $apostrophe));
        return $this;
    }
}

?>
