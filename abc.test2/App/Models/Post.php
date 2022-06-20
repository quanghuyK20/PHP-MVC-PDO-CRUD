<?php

namespace App\Models;

use PDO;
use PDOException;

class Post extends \Core\Model
{


    public static function getAll()
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content, photo FROM posts 
                                ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function addPost($table, $data)
    {

        try {
            $db = static::getDB();
            $keys = implode(",", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));

            $sql = "INSERT INTO $table($keys) VALUES($values)";

            $statement =  $db->prepare($sql);
            foreach ($data as $key => $value) {
                $statement->bindValue(":$key", $value);
            }

            return $statement->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function deletePost($table, $id)
    {

        try {
            $db = static::getDB();

            $sql = "DELETE FROM $table WHERE $id LIMIT 1";

            $db->query($sql);
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function editPost($id)
    {
        try {
            $db = static::getDB();

            $stmt = $db->query("SELECT * FROM posts 
                               WHERE id = $id");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function updatePost($table, $data, $cond)
    {
        try{
            $db = static::getDB();
            $updateKeys = '';
            foreach($data as $key => $value) {
                $updateKeys .= "$key=:$key,";
            }
            $updateKeys = rtrim($updateKeys,","); 
            $sql = "UPDATE $table SET $updateKeys WHERE $cond";
            echo $sql;
            $statement = $db->prepare($sql);
            $statement->bindValue(":$key",$value);
    
            foreach($data as $key => $value) {
                $statement->bindValue(":$key",$value);
            }
    
            return $statement->execute();

        } catch (PDOException $e)
        {
            echo $e->getMessage();
        }
    }
}
