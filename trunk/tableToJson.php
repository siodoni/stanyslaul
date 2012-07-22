<?php

class tableToJson {

    private $tabela = "";

    public function __construct($tabela) {
        $this->tabela;
    }

    public function json() {

        require_once 'lib/Conexao.class.php';
        require_once 'lib/Crud.class.php';

        $con = new conexao();
        $con->connect();

        if ($con->connect() == false) {
            die('Não conectou');
        }
        $query = mysql_query("select column_name from information_schema.columns where table_name='" . $this->tabela . "'");
        $sql = null;

        while ($campo = mysql_fetch_array($query)) {
            if ($sql == null) {
                $sql = $campo['column_name'];
            } else {
                $sql = $sql . ", " . $campo['column_name'];
            }
        }

        $cont = 0;

        while ($campo = mysql_fetch_array($query)) {
            if ($sql == null) {
                $sql = $campo['column_name'];
            } else {
                $sql = $sql . ", " . $campo['column_name'];
            }
        }
    }

}

?>
