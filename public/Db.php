<?php 

class Db
{
    private $pdo;

    private $stmt;
    
    /**
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $param
     */
    public function __construct($dsn, $username, $password, $param = [])
    {
        $this->pdo = new \PDO($dsn, $username, $password, $param);
    }

    /**
     * 
     * @param string $query
     * @param array $param
     * @return Db
     */
    public function query($query, $param = [])
    {
        try {
            $this->stmt = $this->pdo->prepare($query);
            $this->stmt->execute($param);

        } catch (\PDOException $e) {
            throw $e;
        }
        return $this;
    }
    
    /**
     * @return array
     */
    public function fetchAllObj(){
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * @param int $mode
     * @return array
     */
    public function fetchAll($mode = null){
        return $this->stmt->fetchAll($mode);
    }
}