<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header('location:index.php');
}

require_once 'config/Config.class.php';
require_once 'util/Constantes.class.php';
require_once 'conexao/ConexaoPDO.class.php';
require_once 'crud/CrudPDO.class.php';
require_once 'view/Estrutura.class.php';

$estrutura = new Estrutura();

echo "<!DOCTYPE html>";
echo "\n<html>";
echo "\n<head>";
echo $estrutura->scriptV2();
echo $estrutura->cssV2();
echo "\n</head>";
echo "\n<body>";
?>
<body>
    <h2>Basic ComboGrid</h2>
    <p>Click the right arrow button to show the DataGrid.</p>
    <div style="margin:20px 0"></div>
    <select class="easyui-combogrid" style="width:200px" data-options="
            panelWidth: 500,
            idField: 'id',
            textField: 'nome',
            url: 'json.php?idMenu=1',
            method: 'get',
            columns: [[
            {field:'id',title:'ID'},
            {field:'cnpj_cpf',title:'Cnpj Cpf'},
            {field:'id_tp_pessoa',title:'Tp Pessoa',align:'center'},
            {field:'nome',title:'Nome'},
            {field:'dt_nascimento',title:'Dt Nascimento'},
            {field:'sexo',title:'Sexo'},
            {field:'img_foto',title:'Foto'},
            {field:'rg',title:'RG'},
            {field:'somente_teste',title:'Somente Teste'}
            ]],
            fitColumns: true
            ">
    </select>
</body>
<?php
echo "\n</body>";
echo "\n</html>";
