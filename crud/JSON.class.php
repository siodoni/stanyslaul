<?php

class JSON {

    private $tabela = "";
    private $columns = "";
    private $sqlTabela;
    private $sqlColumn = "select lower(b.nome_coluna) nome_coluna, b.titulo_coluna, b.formato_data, b.tipo_dado from #db.snb_dicionario_detalhe b, #db.snb_dicionario a where a.nome_tabela = upper(?) and b.id_dicionario = a.id";
    private $sqlLOV    = "select a.campo_id, a.campo_descricao, a.condicao_filtro, a.ordem from #db.snb_dicionario a where a.nome_tabela = upper(?)";
    private $pdo;
    private $con;
    private $lov = false;

    public function __construct($tabela,$con=null,$lov=false) {
        $this->pdo = new ConexaoPDO("JSON.class.php");
        if ($con != null) {
            $this->con = $con;
        } else {
            $this->con = $this->pdo->connect();
        }
        $this->tabela = $tabela;
        $this->sqlTabela = null;
        $this->lov = $lov;
    }

    public function json($alteraHeader = true) {
        if (empty($this->tabela)) {
            $var = "Acesso Negado!";
        } else {
            if ($alteraHeader) {
                header('Content-type: application/json');
            }

            $this->montarColunas();
            $sql = $this->lov ? $this->sqlTabela : ("select " . $this->sqlTabela . " from " . Config::DBNAME . "." . $this->tabela . " order by 1");
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
                . ($row->condicao_filtro == null ? "" : " " . $row->condicao_filtro)
                . ($row->ordem           == null ? "" : " " . $row->ordem);
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