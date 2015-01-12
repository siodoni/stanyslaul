<?php

/** Classe CRUD - Create, Recovery, Update and Delete
 * @author - Rodolfo Leonardo Medeiros
 * @date - 25/09/2009
 * Arquivo - codigo.class.php
 * @package crud
 */
class Crud {

    private $sql_ins = "";
    private $tabela = "";
    private $sql_sel = "";
    private $redireciona = true;

    // Caso pretendamos que esta classe seja herdada por outras, então alguns atributos podem ser protected

    /** Método construtor
     * @method __construct
     * @param string $tabela
     * @return $this->tabela
     */
    // construtor, nome da tabela como parametro
    public function __construct($tabela,$redireciona=true) {
        $this->tabela = $tabela;
        $this->redireciona = $redireciona;
        return $this->tabela;
    }

    /** Método inserir
     * @method inserir
     * @param string $campos
     * @param string $valores
     * @example: $campos = "codigo, nome, email" e $valores = "1, 'João Brito', 'joao@joao.net'"
     * @return void
     */
    // funçao de inserçao, campos e seus respectivos valores como parametros
    public function inserir($campos, $valores) {
        $this->sql_ins = "insert into " . Config::DBNAME . "." . $this->tabela . " ($campos) values ($valores)";
        
        //die($this->sql_ins);

        if (!$this->ins = mysql_query($this->sql_ins)) {
            die("Erro na inclus&atilde;o " . '<br>Linha: ' . __LINE__ . "<br>" . mysql_error() . "<br>"
               ."comando ". $this->sql_ins . "<br>"
               . ($this->redireciona ? "<a href='list.php'>Voltar ao Menu</a>" : ""));
        } else {
            $_SESSION['mensagemRetorno'] = Constantes::GRAVAR;
            print ($this->redireciona ? "<script>location='list.php';</script>" : "");
        }
    }

    // funçao de ediçao, campos com seus respectivos valores e o campo id que define a linha a ser editada como parametros
    public function atualizar($camposvalores, $where = NULL, $mostrarMensagem = false) {
        if ($where) {
            $this->sql_upd = "update " . Config::DBNAME . "." . $this->tabela . " set $camposvalores where $where";
        } else {
            $this->sql_upd = "update " . Config::DBNAME . "." . $this->tabela . " set $camposvalores";
        }

        //die($this->sql_upd);
        
        if (!$this->upd = mysql_query($this->sql_upd)) {
            die("<center>Erro na atualiza&ccedil;&atilde;o " 
              . "<br>Linha:  " . __LINE__ 
              . "<br>Erro:   " . mysql_error() 
              . "<br>Campos: " . $camposvalores 
              . "<br>Where:  " . $where
              . "<br>" . $mostrarMensagem ? $this->sql_upd : ""
              . ($this->redireciona ? "<br><a href='list.php'>Voltar ao Menu</a>" : "" ) . "</center>");
        } else {
            if ($mostrarMensagem) {
                $_SESSION['mensagemRetorno'] = Constantes::ATUALIZAR;
                print ($this->redireciona ? "<center>Registro Atualizado com Sucesso!<br><a href='list.php'>Voltar ao Menu</a></center>" : "");
            }
        }
    }

    /** Método excluir
     * @method excluir
     * @param string $where
     * @example: $where = " codigo=2 AND nome='João' "
     * @return void
     */
    // funçao de exclusao, campo que define a linha a ser editada como parametro
    public function excluir($where = NULL) {
        if ($where) {
            $this->sql_sel = "select * from " . Config::DBNAME . "." . $this->tabela . " where $where";
            $this->sql_del = "delete from " . Config::DBNAME . "." . $this->tabela . " where $where";
        } else {
            $this->sql_sel = "select * from " . Config::DBNAME . "." . $this->tabela;
            $this->sql_del = "delete from " . Config::DBNAME . "." . $this->tabela;
        }
        $sel = mysql_query($this->sql_sel);
        $regs = mysql_num_rows($sel);

        if ($regs > 0) {
            if (!$this->del = mysql_query($this->sql_del)) {
                die("<center>Erro na exclus&atilde;o " . '<br>Linha: ' . __LINE__ . "<br>" . mysql_error() . "<br>"
                  . ($this->redireciona ? "<a href='list.php'>Voltar ao Menu</a>" : "" ) . "</center>");
            } else {
                print ($this->redireciona ? "<center>Registro Excluido com Sucesso!<br><a href='list.php'>Voltar ao Menu</a></center>" : "");
            }
        } else {
            print "<center>Registro N&atilde;o Encontrado!<br>".($this->redireciona ? "<a href='menu.php?'>Voltar ao Menu</a>": "") . "</center>";
        }
    }
}