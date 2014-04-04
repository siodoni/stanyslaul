<?php
class Conexao {

    private $dbHost = 'localhost'; // servidor
    private $dbUser = 'root';      // usuario do banco
    private $dbPass = 'vertrigo';  // senha do usuario do banco
    private $dbName = 'newyork';   // nome do banco
    private $con = false;

    public function connect() { // estabelece conexao
        if(!$this->con) {
            $myconn = @mysql_connect($this->dbHost,$this->dbUser,$this->dbPass);
            if($myconn) {
                $seldb = @mysql_select_db($this->dbName,$myconn);
                if($seldb) {
                    $this->con = true;
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    public function disconnect() { // fecha conexao
        if($this->con) {
            if(@mysql_close()) {
                $this->con = false;
                return true;
            }
            else {
                return false;
            }
        }
    }

    public function getDbName(){
        return $this->dbName;
    }
}
?>