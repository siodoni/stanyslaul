<?php

class JSON {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;
    private $sqlColumn = Constantes::QUERY_DICIONARIO_COL;
    private $sqlLOV    = Constantes::QUERY_DICIONARIO_LOV;
    private $sqlDic    = Constantes::QUERY_NM_TAB_DICIONARIO;
    private $pdo;
    private $con;
    private $lov = false;
    private $dataTable = false;

    public function __construct($tabela,$con=null,$lov=false,$dataTable=false) {
        $this->pdo = new ConexaoPDO("JSON.class.php");
        if ($con != null) {
            $this->con = $con;
        } else {
            $this->con = $this->pdo->connect();
        }
        $this->tabela = $tabela;
        $this->sqlTabela = null;
        $this->lov = $lov;
        $this->dataTable = $dataTable;
    }

    public function json($alteraHeader = true) {
        $sql="";
        if (empty($this->tabela)) {
            $var = "Acesso Negado!";
        } else {
            try {
                if ($alteraHeader) {
                    header('Content-type: application/json');
                }
                $rsT = $this->con->prepare(str_replace("#db",Config::DBNAME,$this->sqlDic));
                $rsT->bindParam(1, $this->tabela);
                $rsT->execute();
                $tabelaDic = $rsT->fetch(PDO::FETCH_OBJ);
                
                $this->montarColunas();
                $sql = $this->lov ? $this->sqlTabela : ("select " . $this->sqlTabela . " from " . Config::DBNAME . "." . $tabelaDic->nome_tabela . " order by 1");
                $rs = $this->con->prepare($sql);
                $rs->execute();
                $linha = array();

                if ($rs->execute() && $rs->rowCount() > 0) {
                    while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                        $linha[] = array_map('utf8_encode', $row);
                    }
                }

                $var = json_encode($linha, JSON_NUMERIC_CHECK);
                return ($this->dataTable ? "{\"data\":" : "") . $var . ($this->dataTable ? "}" : "");
            } catch (Exception $e) {
                die("Problema no metodo JSON.json</p><p>".$sql."</p><p>".$e."</p>");
            }
        }
    }

    public function columns() {
        $this->montarColunas();
        $this->columns = "\n\t\tcolumns:\n\t\t\t[" . $this->columns . "],\n";
        return $this->columns;
    }

    private function montarColunas() {
        $sql = "";
        $tab = $this->tabela;
        $this->sqlTabela = null;
        $this->columns = null;

        if ($this->lov) {
            $rs = $this->con->prepare(str_replace("#db",Config::DBNAME,($this->sqlLOV)));
            $rs->bindParam(1, $tab);
            $rs->execute();
            $row = $rs->fetch(PDO::FETCH_OBJ);
            $sql = "select " . $row->campo_id . " as id__tabela__lov " 
                . ($row->campo_descricao == null ? ", 'Descricao ausente! Corrija o dicionario de dados!' as descricao " : ",".$row->campo_descricao)
                . " from " . Config::DBNAME . "." . $tab
                . ($row->condicao_filtro == null ? "" : " where " . $row->condicao_filtro)
                . ($row->ordem           == null ? "" : " order by " . $row->ordem);
            //echo strtolower($sql);
            $this->sqlTabela = strtolower($sql);
        } else {
            $sql = $this->sqlColumn;
            $rs = $this->con->prepare(str_replace("#db",Config::DBNAME,($sql)));
            $rs->bindParam(1, $tab);
            $substr = "if (length(#)>80,concat(substr(#,1,77),'...'),#) as # ";

            if ($rs->execute() && $rs->rowCount() > 0) {
            
                while ($row = $rs->fetch(PDO::FETCH_OBJ)) {
                    $dataType = "";

                    if ($row->formato_data != null) {
                        $dataType = "date_format(" . $row->nome_coluna . ",'" . $row->formato_data . "') as " . $row->nome_coluna;
                    } else if ($row->tipo_dado == "TEXTO"
                            || $row->tipo_dado == "TEXTO LONGO") {
                        $dataType = str_replace("#", $row->nome_coluna, $substr);
                    } else {
                        $dataType = $row->nome_coluna;
                    }

                    if ($this->sqlTabela == null) {
                        $this->sqlTabela = $dataType;
                        $this->columns = "{field: '" . $row->nome_coluna . "', headerText: '" . $row->titulo_coluna . "', sortable: true}";
                    } else {
                        $this->sqlTabela .= ", " . $dataType;
                        $this->columns = $this->columns . "\n\t\t\t,{field: '" . $row->nome_coluna . "', headerText: '" . $row->titulo_coluna . "', sortable: true}";
                    }
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