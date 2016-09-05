<?php

namespace Yurii\Model;

use Yurii\DI\Service;
use Yurii\Exception\DatabaseException;

/**
 * Class ActiveRecord
 * @package Yurii\Model
 *
 * main object for work with db
 */
abstract class ActiveRecord {
    protected static $db; //PDO object with db connection

    public static function getDBCon(){
        if(empty(self::$db)){
            self::$db = Service::get('db')->getConnection();
        }
        return self::$db;
    }

    public static function getTable() {}

    public static function getClass() {
        return get_called_class();
    }

    public static function getUserEmailById($id) { //get user email by user_id
        $sql = "SELECT * FROM users WHERE id=" . $id;
        $query = self::getDBCon()->prepare($sql);
        $query->execute();
        $result = $query->fetch()['email'];

        if(empty($result)) {
            $result = 'Unknown user';
        }
        return $result;
    }

    public static function findByEmail($email) { //find user by email
        $table = static::getTable(); // Prepare SQL request
        $sql = "SELECT * FROM " . $table . " WHERE email='" . $email . "'";
        $query = self::getDBCon()->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(\PDO::FETCH_CLASS, static::getClass());

        if ($query->rowCount() == 0) {
            return false;
        }

        return $result[0];
    }

    /**
     * @param string $mode
     * @return mixed
     * @throws DatabaseException
     *
     * find data in db by id, return single object or array of object depending on $mode
     */
    public static function find($mode = 'all', $start = null, $limit = null){
        $table = static::getTable(); // Prepare SQL request
        $sql = "SELECT * FROM " . $table;

        if(is_numeric($mode)){
            $sql .= " WHERE id=?";//.(int)$mode;
        }

        if (!is_null($start) && !is_null($limit)) {
            $sql .= " LIMIT $start, $limit";
        }

        $query = self::getDBCon()->prepare($sql);
        empty($mode) ? $query->execute() : $query->execute(array($mode));

        if ($query->rowCount() == 0) {
            throw new DatabaseException(202);
        }

        $result = $query->fetchAll(\PDO::FETCH_CLASS, static::getClass()); //Create array of result objects

        if(is_numeric($mode)){ // if we looking single post - return one object, else array of objects
            $result = $result[0];
        }
        return $result;
    }

    public function getFields(){ // return associative array of property and it value
        return get_object_vars($this);
    }

    /**
     * @throws DatabaseException
     *
     * save new or update row in db
     */
    public function save(){
        $fields = $this->getFields();

        foreach($fields as $field => $value) {
            if(!isset($value)) {
                unset($fields[$field]);
            }
        }

        $sql = (array_key_exists('id', $fields)) ? $this->prepareUpdate($fields) : $this->prepareInsert($fields);

        $query = self::getDBCon()->prepare($sql);

        $query->execute($fields);
        if ($query->errorCode() != 00000) {
            throw new DatabaseException($query->errorInfo()[2]);
        }
    }

    /**
     * @param $fields
     * @return mixed|string
     *
     * prepare query for insert action
     */
    private function prepareInsert($fields) {
        $sql = 'INSERT INTO ' . static::getTable() . ' (';

        foreach($fields as $field => $value) {
            $sql .= $field . ",";
        }
        $sql = preg_replace("/,$/", ')  VALUE(', $sql);

        foreach($fields as $field => $value) {
            $sql .= " :" . $field . ",";
        }

        $sql = preg_replace("/,$/", ')', $sql);

        return $sql;
    }

    /**
     * @param $fields
     * @return mixed|string
     *
     * prepare query for update action
     */
    private function prepareUpdate($fields) {
        $sql = 'UPDATE ' . static::getTable() . ' SET ';

        foreach($fields as $field => $value) {
            if ($field != 'id') {
                $sql .= $field . '=:' . $field . ', ';
            }
        }

        $sql = preg_replace("/, $/", ' WHERE id=:id', $sql);

        return $sql;
    }
}





















