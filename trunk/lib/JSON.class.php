<?php

class JSON {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;

    public function __construct($tabela) {
        $this->tabela = $tabela;
        $this->sqlTabela = null;
    }

    public function json() {
        if (empty($this->tabela)) {
            $var = "acesso negado";
        } else {
            require_once 'Conexao.class.php';
            header('Content-type: application/json');

            $con = new conexao();
            $con->connect();

            if ($con->connect() == false) {
                die('Não conectou');
            }

            $orderBy = " order by 1";
            $query = mysql_query("select column_name " .
                    " from information_schema.columns " .
                    " where table_schema = '" . $con->getDbName() . "' " .
                    " and table_name   = '" . $this->tabela . "'");

            $this->montarColunas($query);
            $sql = "select " . $this->sqlTabela . " from " .$con->getDbName().".".$this->tabela . $orderBy;
            $c = mysql_query($sql);
            $linha = array();

            while ($r = mysql_fetch_assoc($c)) {
                $linha[] = array_map('utf8_encode', $r);
            }

            $var = json_encode($linha);
            $con->disconnect();
        }
        return $var;
    }

    public function columns() {
        require_once 'Conexao.class.php';
        $con = new conexao();
        $con->connect();

        $query = mysql_query("select column_name " .
                " from information_schema.columns " .
                " where table_schema = '" . $con->getDbName() . "' " .
                " and table_name   = '" . $this->tabela . "'");

        $this->montarColunas($query);

        $this->columns = "columns:\n[" . $this->columns . "],\n";
        $con->disconnect();
        return $this->columns;
    }

    public function getTabela() {
        return $this->tabela;
    }

    private function montarColunas($query) {

        while ($campo = mysql_fetch_array($query)) {
            if ($this->sqlTabela == null) {
                $this->sqlTabela = $campo['column_name'];
                $this->columns = "{field: '" . $campo['column_name'] . "', headerText: '" . ucfirst(str_replace("_", " ", $campo['column_name'])) . "', sortable: true}\n";
            } else {
                $this->sqlTabela .= ", " . $campo['column_name'];
                $this->columns = $this->columns . ",{field: '" . $campo['column_name'] . "', headerText: '" . ucfirst(str_replace("_", " ", $campo['column_name'])) . "', sortable: true}\n";
            }
        }
    }
}