<?php

//http://code.tutsplus.com/pt/tutorials/pdo-vs-mysqli-which-should-you-use--net-24059
class ConexaoPDO {

    private static $con = null;
    private static $cont = 0;
    private static $linha = 0;
    private static $time;
    private $local;

    public function __construct($local) {
        $this->local = $local;
    }
    
    public function connect() {
        try {
            static::$linha++;
            if (static::$con == null) {
                static::$con = new PDO("mysql:host=" . Constantes::HOST . ";dbname=" . Constantes::DBNAME, Constantes::USER, Constantes::PASSWORD);
                static::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                static::$cont++;
                static::$time = time();
            }

            $file = fopen("conexao.log", "a");
            fwrite($file,"qtde ".static::$cont." linha " . static::$linha . " time " . static::$time . " local " . $this->local . " \r\n");
            fclose($file);

            return static::$con;
        } catch (PDOException $e) {
            print "<code>" . $e->getMessage() . "</code>";
            return null;
        }
    }

    public function disconnect() {
        static::$con = null;
        return true;
    }
}