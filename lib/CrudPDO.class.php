<?php

/** Classe CRUD - Create, Recovery, Update and Delete
 * @author - Rodolfo Leonardo Medeiros
 * @date - 25/09/2009
 * Arquivo - codigo.class.php
 * @package crud
 */
class CrudPDO {

    private $con;
    private $sql_ins = "";
    private $tabela = "";
    private $redireciona = true;

    // Caso pretendamos que esta classe seja herdada por outras, então alguns atributos podem ser protected

    /** Método construtor
     * @method __construct
     * @param string $tabela
     * @return $this->tabela
     */
    // construtor, nome da tabela como parametro
    public function __construct($con, $tabela, $redireciona = true) {
        $this->tabela = $tabela;
        $this->redireciona = $redireciona;
        $this->con = $con;
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
        try {
            $this->sql_ins = "insert into " . Constantes::DBNAME . "." . $this->tabela . " ($campos) values ($valores)";
            $rs = $this->con->prepare($this->sql_ins);
            $rs->execute();
            $_SESSION['mensagemRetorno'] = Constantes::GRAVAR;
            print ($this->redireciona ? "<script>location='list.php';</script>" : "");
        } catch (PDOException $e) {
            die("Erro na inclus&atilde;o " .
            '<br>Linha: ' . __LINE__ . "<br>" . $e->getMessage() . "<br>"
            . "comando " . $this->sql_ins . "<br>"
            . ($this->redireciona ? "<a href='list.php'>Voltar ao Menu</a>" : ""));
        }
    }

    // funçao de ediçao, campos com seus respectivos valores e o campo id que define a linha a ser editada como parametros
    public function atualizar($camposvalores, $where = NULL, $mostrarMensagem = false) {
        try {
            if ($where) {
                $this->sql_upd = "update " . Constantes::DBNAME . "." . $this->tabela . " set $camposvalores where $where";
            } else {
                $this->sql_upd = "update " . Constantes::DBNAME . "." . $this->tabela . " set $camposvalores";
            }
            $rs = $this->con->prepare($this->sql_upd);
            $rs->execute();

            if ($mostrarMensagem) {
                $_SESSION['mensagemRetorno'] = Constantes::ATUALIZAR;
                print ($this->redireciona ? "<center>Registro Atualizado com Sucesso!<br><a href='list.php'>Voltar ao Menu</a></center>" : "");
            }
        } catch (PDOException $e) {
            die("<center>Erro na atualiza&ccedil;&atilde;o "
                    . "<br>Linha:  " . __LINE__
                    . "<br>Erro:   " . $e->getMessage()
                    . "<br>Campos: " . $camposvalores
                    . "<br>Where:  " . $where
                    . "<br>" . $mostrarMensagem ? $this->sql_upd : ""
                            . ($this->redireciona ? "<br><a href='list.php'>Voltar ao Menu</a>" : "" ) . "</center>");
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
        try {
            if ($where) {
                $this->sql_del = "delete from " . Constantes::DBNAME . "." . $this->tabela . " where $where";
            } else {
                $this->sql_del = "delete from " . Constantes::DBNAME . "." . $this->tabela;
            }

            $rs = $this->con->prepare($this->sql_del);
            $rs->execute();

            print ($this->redireciona ? "<center>Registro Excluido com Sucesso!<br><a href='list.php'>Voltar ao Menu</a></center>" : "");
            //print "<center>Registro N&atilde;o Encontrado!<br>".($this->redireciona ? "<a href='menu.php?'>Voltar ao Menu</a>": "") . "</center>";
        } catch (PDOException $e) {
            die("<center>Erro na exclus&atilde;o " . '<br>Linha: ' . __LINE__ . "<br>" . $e->getMessage() . "<br>"
                    . ($this->redireciona ? "<a href='list.php'>Voltar ao Menu</a>" : "" ) . "</center>");
        }
    }

}
