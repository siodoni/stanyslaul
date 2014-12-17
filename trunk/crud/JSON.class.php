<?php

class JSON {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;
    private $sqlColumn = "select column_name, lower(data_type) data_type from information_schema.columns where table_schema = ? and table_name = ? ";
    private $pdo;
    private $con;

    public function __construct($tabela,$con=null) {
        $this->pdo = new ConexaoPDO("JSON.class.php");
        if ($con != null) {
            $this->con = $con;
        } else {
            $this->con = $this->pdo->connect();
        }
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
            $sql = "select " . $this->sqlTabela . " from " . Config::DBNAME . "." . $this->tabela . " order by 1";            
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
        $this->columns = "\n\t\tcolumns:\n\t\t\t[" . $this->columns . "],\n";
        return $this->columns;
    }

    private function montarColunas() {
        $banco = Config::DBNAME;
        $tab = $this->tabela;
        $rs = $this->con->prepare($this->sqlColumn);
        $rs->bindParam(1, $banco);
        $rs->bindParam(2, $tab);
        $substr = "if (length(#)>80,concat(substr(#,1,77),'...'),#) as # ";

        if ($rs->execute() && $rs->rowCount() > 0) {
            while ($row = $rs->fetch(PDO::FETCH_OBJ)) {
                $dataType = "";
                
                if ($row->data_type == 'date') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::DATE_FORMAT . "') as " . $row->column_name;
                } else if ($row->data_type == 'datetime') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::DATETIME_FORMAT . "') as " . $row->column_name;
                } else if ($row->data_type == 'time') {
                    $dataType = "date_format(" . $row->column_name . ",'" . Constantes::TIME_FORMAT . "') as " . $row->column_name;
                } else if ($row->data_type == 'char'
                        || $row->data_type == 'varchar'
                        || $row->data_type == 'text'
                        || $row->data_type == 'longtext'
                        || $row->data_type == 'enum') {
                    $dataType = str_replace("#", $row->column_name, $substr);
                } else {
                    $dataType = $row->column_name;
                }

                if ($this->sqlTabela == null) {
                    $this->sqlTabela = $dataType;
                    $this->columns = "{field: '" . $row->column_name . "', headerText: '" . ucfirst(str_replace("_", " ", $row->column_name)) . "', sortable: true}";
                } else {
                    $this->sqlTabela .= ", " . $dataType;
                    $this->columns = $this->columns . "\n\t\t\t,{field: '" . $row->column_name . "', headerText: '" . ucfirst(str_replace("_", " ", $row->column_name)) . "', sortable: true}";
                }
            }
        }
    }

    public function getTabela() {
        return $this->tabela;
    }
    
    public function __destruct() {
        $this->pdo->disconnect();
    }
}