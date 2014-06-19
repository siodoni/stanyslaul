<?php

class JSON extends Constantes {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;

    public function __construct($tabela) {
        $this->tabela = $tabela;
        $this->sqlTabela = null;
    }

    public function json($alteraHeader = true, $connectar = true) {
        if (empty($this->tabela)) {
            $var = "Acesso Negado!";
        } else {
            require_once 'Conexao.class.php';
            if ($alteraHeader){
                header('Content-type: application/json');
            }
            
            if ($connectar) {
                $con = new conexao();
                $con->connect();
            }

            $orderBy = " order by 1";
            $query = mysql_query(
                    "select column_name, lower(data_type) data_type " .
                    "  from information_schema.columns " .
                    " where table_schema = '".parent::DBNAME."' " .
                    "   and table_name   = '".$this->tabela."' ");

            $this->montarColunas($query);
            $sql = "select " . $this->sqlTabela . " from " .parent::DBNAME.".".$this->tabela . $orderBy;
            $c = mysql_query($sql);
            $linha = array();

            while ($r = mysql_fetch_assoc($c)) {
                $linha[] = array_map('utf8_encode', $r);
            }

            $var = json_encode($linha,JSON_NUMERIC_CHECK);
            if ($connectar) {
                $con->disconnect();
            }
        }
        return $var;
    }

    public function columns($connectar = true) {
        require_once 'Conexao.class.php';
        if ($connectar) {
            $con = new conexao();
            $con->connect();
        }

        $query = mysql_query(
                "select column_name, lower(data_type) data_type " .
                "  from information_schema.columns " .
                " where table_schema = '".parent::DBNAME."' " .
                "   and table_name   = '".$this->tabela."' ");

        $this->montarColunas($query);

        $this->columns = "columns:\n[" . $this->columns . "],\n";
        if ($connectar) {
            $con->disconnect();
        }
        return $this->columns;
    }

    public function getTabela() {
        return $this->tabela;
    }

    private function montarColunas($query) {

        while ($campo = mysql_fetch_array($query)) {
            $dataType = "";
            
            if ($campo['data_type'] == 'date') {
                $dataType = "date_format(".$campo['column_name'].",'".parent::DATE_FORMAT."') as ".$campo['column_name'];
            } else if ($campo['data_type'] == 'datetime') {
                $dataType = "date_format(".$campo['column_name'].",'".parent::DATETIME_FORMAT."') as ".$campo['column_name'];
            } else if ($campo['data_type'] == 'time') {
                $dataType = "date_format(".$campo['column_name'].",'".parent::TIME_FORMAT."') as ".$campo['column_name'];
            } else {
                $dataType = $campo['column_name'];
            }

            if ($this->sqlTabela == null) {
                $this->sqlTabela = $dataType;
                $this->columns = "{field: '" . $campo['column_name'] . "', headerText: '" . ucfirst(str_replace("_", " ", $campo['column_name'])) . "', sortable: true}\n";
            } else {
                $this->sqlTabela .= ", " . $dataType;
                $this->columns = $this->columns . ",{field: '" . $campo['column_name'] . "', headerText: '" . ucfirst(str_replace("_", " ", $campo['column_name'])) . "', sortable: true}\n";
            }
        }
    }
}