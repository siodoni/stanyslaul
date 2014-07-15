<?php

//http://code.tutsplus.com/pt/tutorials/pdo-vs-mysqli-which-should-you-use--net-24059
class ConexaoPDO extends Constantes {

    private $con;

    public function connect() {
        try {
            $this->con = new PDO("mysql:host=" . parent::HOST . ";dbname=" . parent::DBNAME, parent::USER, parent::PASSWORD);
            return $this->con;
        } catch (PDOException $e) {
            print "<code>" . $e->getMessage() . "</code>";
            return null;
        }
    }

    public function disconnect() {
        $this->con = null;
        return true;
    }
}