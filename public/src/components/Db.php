<?php 
namespace components;

use PDO;

class Db
{
    private $pdo;
    
    private $stmt;
    
    public function __construct($param = [])
    {
        $this->pdo = new PDO($param['dsn'], $param['username'], $param['password']);
        $this->pdo->exec("SET NAMES {$param['charset']}");
        
    }

    public function query($query, $param = [])
    {
        try {
            $args = func_get_args();
            //array_shift($args);
            $this->stmt = $this->pdo->prepare($query);
            //dd($args);
            $this->stmt->execute($param);
            
        } catch (\PDOException $e) {
            throw $e;
        }
        return $this;
    }
    
    public function fetchAllObj(){
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function fetchAll($mode = null){
        //return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        //return $this->stmt->fetchAll(PDO::FETCH_OBJ | PDO::FETCH_UNIQUE);
        return $this->stmt->fetchAll($mode);
    }
    
    
    public function getAll($table, $sql = null, $param = [])
    {
        $this->query("SELECT * FROM $table " . $sql, $param);
        return $this;
    }
}