<?php
require_once 'lib/Conexao.class.php';
require_once 'lib/Crud.class.php';

$con = new conexao();
$con->connect();

if ($con->connect() == false) {
    die('Não conectou');
}

if (!isset($_REQUEST["nomeTabela"])) {
    die("Informe o parametro nomeTabela para que a pagina seja renderizada.");
}

$nomeTabela = $_REQUEST["nomeTabela"];
$orderBy = " order by 1";

$query = mysql_query("select column_name, 
                                 column_key, 
                                 data_type 
                            from information_schema.columns 
                           where table_name='" . $nomeTabela . "'
                             and substr(column_name,1,1) <> '_'");
$sql = null;

while ($campo = mysql_fetch_array($query)) {
    if (($campo['column_key'] != 'PRI') || (substr($campo['column_name'],0,1) != "_")) {
        if ($sql == null) {
            $sql = $campo['column_name'];
        } else {
            $sql = $sql . ", " . $campo['column_name'];
        }
    }

    if (($campo['data_type'] != 'longtext') || ($campo['column_key'] != 'PRI') || (substr($campo['column_name'],0,1) != "_")) {
        $arrColuna[] = $campo['column_name'];
    }
}
$sql = "select " . $sql . " from " . $nomeTabela . $orderBy;
$query = mysql_query($sql);
?>

<table id="tableResult">
    <thead>
        <?php
        foreach ($arrColuna as $arrayColuna) {
            echo "<th data-field='$arrayColuna'>" . ucwords(str_replace("_", " ", $arrayColuna)) . "</th>\n";
        }
        ?>
    </thead>
    <tbody>
        <?php
        while ($campo = mysql_fetch_array($query)) {
            echo("<tr>\n");
            foreach ($arrColuna as $arrayColuna) {
                echo "<td>{$campo[$arrayColuna]}</td>\n";
            }
        }
        ?>
    </tbody>
</table>
<?php
$con->disconnect();
?>
