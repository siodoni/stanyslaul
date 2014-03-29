<?php
class JSON {

    private $tabela = "";
    private $columns = "";

    public function __construct($tabela) {
        $this->tabela = $tabela;
    }

    public function json() {
        if (empty($this->tabela)){
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
            $query = mysql_query("select column_name ".
                                  " from information_schema.columns ".
                                 " where table_schema = '".$con->getDbName()."' ".
                                   " and table_name   = '".$this->tabela."'");
            $sql = null;

            while ($campo = mysql_fetch_array($query)) {
                if ($sql == null) {
                    $sql = $campo['column_name'];
                    $this->columns = "{field: '".$campo['column_name']."', headerText: '".$campo['column_name']."', sortable: true}\n";
                } else {
                    $sql = $sql.", ".$campo['column_name'];
                    $this->columns = $this->columns.",{field: '".$campo['column_name']."', headerText: '".$campo['column_name']."', sortable: true}\n";
                }
            }

            $sql = "select ".$sql." from ".$this->tabela.$orderBy;
            $c = mysql_query($sql);
            $linha = array();

            while ($r = mysql_fetch_assoc($c)) {
                $linha[] = $r;
            }

            $var = json_encode($linha);
            $con->disconnect();
        }
        return $var;
    }

    public function columns(){
        require_once 'Conexao.class.php';
        $con = new conexao();
        $con->connect();

        $query = mysql_query("select column_name ".
                              " from information_schema.columns ".
                             " where table_schema = '".$con->getDbName()."' ".
                               " and table_name   = '".$this->tabela."'");

        while ($campo = mysql_fetch_array($query)) {
            if ($this->columns == null) {
                $this->columns = "{field: '".$campo['column_name']."', headerText: '".$campo['column_name']."', sortable: true}\n";
            } else {
                $this->columns = $this->columns.",{field: '".$campo['column_name']."', headerText: '".$campo['column_name']."', sortable: true}\n";
            }
        }

        $this->columns = "columns:\n[".$this->columns."],\n";
        $con->disconnect();
        return $this->columns;
    }
    
    public function getTabela(){
        return $this->tabela;
    }
}
?>
