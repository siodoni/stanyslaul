<?php

class JSON extends Constantes {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;
    private $sqlColumn = "select column_name, lower(data_type) data_type from information_schema.columns where table_schema = '#ts' and table_name = '#tn' ";

    public function __construct($tabela) {
        $this->tabela = $tabela;
        $this->sqlTabela = null;
    }

    public function json($alteraHeader = true, $connectar = true) {
        if (empty($this->tabela)) {
            $var = "Acesso Negado!";
        } else {
            if ($alteraHeader) {
                header('Content-type: application/json');
            }

            if ($connectar) {
                $con = new Conexao();
                $con->connect();
            }

            $this->queryColunas();

            $sql = "select " . $this->sqlTabela . " from " . parent::DBNAME . "." . $this->tabela . " order by 1";
            $c = mysql_query($sql);
            $linha = array();

            while ($r = mysql_fetch_assoc($c)) {
                $linha[] = array_map('utf8_encode', $r);
            }

            $var = json_encode($linha, JSON_NUMERIC_CHECK);

            if ($connectar) {
                $con->disconnect();
            }
        }
        return $var;
    }

    public function columns($connectar = true) {
        if ($connectar) {
            $con = new conexao();
            $con->connect();
        }

        $this->queryColunas();

        $this->columns = "columns:\n[" . $this->columns . "],\n";
        if ($connectar) {
            $con->disconnect();
        }
        return $this->columns;
    }

    public function getTabela() {
        return $this->tabela;
    }

    private function queryColunas() {
        $query = mysql_query(str_replace("#tn", $this->tabela, str_replace("#ts", parent::DBNAME, $this->sqlColumn)));
        $this->montarColunas($query);
    }

    private function montarColunas($query) {

        while ($campo = mysql_fetch_array($query)) {
            $dataType = "";

            if ($campo['data_type'] == 'date') {
                $dataType = "date_format(" . $campo['column_name'] . ",'" . parent::DATE_FORMAT . "') as " . $campo['column_name'];
            } else if ($campo['data_type'] == 'datetime') {
                $dataType = "date_format(" . $campo['column_name'] . ",'" . parent::DATETIME_FORMAT . "') as " . $campo['column_name'];
            } else if ($campo['data_type'] == 'time') {
                $dataType = "date_format(" . $campo['column_name'] . ",'" . parent::TIME_FORMAT . "') as " . $campo['column_name'];
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