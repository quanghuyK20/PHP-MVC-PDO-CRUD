<?php

namespace App\Models;

use PDO;
use PDOException;
//use APP\Config;;

class Post extends \Core\Model
{


    public static function getAll()
    {
        //$host = 'localhost';
        //$dbname = 'mvc';
        //$username = 'root';
        //$password = 'rootroot';
        
        try {
            //$db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
            //              $username, $password);
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts 
                                ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
