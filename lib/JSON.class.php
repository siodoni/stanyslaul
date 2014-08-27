<?php

class JSON {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;
    private $sqlColumn = "select column_name, lower(data_type) data_type from information_schema.columns where table_schema = ? and table_name = ? ";
    private $pdo;
    private $con;

    public function __construct($tabela) {
        $this->pdo = new ConexaoPDO();
        $this->con = $this->pdo->connect();
        $this->tabela = $tabela;
        $this->sqlTabela = null;
    }

    public function json($alteraHeader = true) {
        if (empty($this->tabela)) {
            $var = "Acesso Negado!";
        } else {
            if ($alteraHeader) {
                header('Content-type: application/json');
            }

            $this->montarColunas();
            $sql = "select " . $this->sqlTabela . " from " . Constantes::DBNAME . "." . $this->tabela . " order by 1";
            $rs = $this->con->prepare($sql);
            $rs->execute();
            $linha = array();

            if ($rs->execute() && $rs->rowCount() > 0) {
                while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $linha[] = array_map('utf8_encode', $row);
                }
            }
            
            $var = json_encode($linha, JSON_NUMERIC_CHECK);
        }
        return $var;
    }

    public function columns() {
        $this->montarColunas();
        $this->columns = "columns:\n[" . $this->columns . "],\n";
        return $this->columns;
    }

    private function montarColunas() {
        $banco = Constantes::DBNAME;
        $tab = $this->tabela;
        $rs = $this->con->prepare($this->sqlColumn);
        $rs->bindParam(1, $banco);
        $rs->bindParam(2, $tab);

        if ($rs->execute() && $rs->rowCount() > 0) {
            while ($row = $rs->fetch(PDO::FETCH_OBJ)) {
                $dataType = "";

                if ($row->data_type == 'date') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::DATE_FORMAT . "') as " . $row->column_name;
                } else if ($row->data_type == 'datetime') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::DATETIME_FORMAT . "') as " . $row->column_name;
                } else if ($row->data_type == 'time') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::TIME_FORMAT . "') as " . $row->column_name;
                } else {
                    $dataType = $row->column_name;
                }

                if ($this->sqlTabela == null) {
                    $this->sqlTabela = $dataType;
                    $this->columns = "{field: '" . $row->column_name . "', headerText: '" . ucfirst(str_replace("_", " ", $row->column_name)) . "', sortable: true}\n";
                } else {
                    $this->sqlTabela .= ", " . $dataType;
                    $this->columns = $this->columns . ",{field: '" . $row->column_name . "', headerText: '" . ucfirst(str_replace("_", " ", $row->column_name)) . "', sortable: true}\n";
                }
            }
        }
        $this->pdo->disconnect();
    }

    public function getTabela() {
        return $this->tabela;
    }

}
