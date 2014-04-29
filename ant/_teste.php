<!DOCTYPE html>
<html>
    <head>
        <title>Stanyslaul</title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="author"       content="siodoni.com.br"/>
        <link rel="stylesheet"    type="text/css" href="css/style.css"/>
        <link rel="stylesheet"    type="text/css" href="css/table.css"/>
        <script type="text/javascript" src="js/site.js"></script>
        <script type="text/javascript" src="js/jquery.validation.ajax.js"></script>
        <script type="text/javascript" src="js/jquery.validation.js"></script>

        <script src="kendo/js/jquery.min.js"></script>
        <script src="kendo/js/kendo.web.min.js"></script>
        <link href="kendo/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendo/styles/kendo.default.min.css" rel="stylesheet" />

        <script type="text/javascript">
            $(document).ready(function() { 
                $("#editor").kendoEditor();
            }); 
        </script>
    </head>
    <body id="admin">
        <form id="" action="update.php?comando=update&campoId=id_artigo&id=13&nomeTabela=fat_artigo" method="post">
            <table id='hor-minimalist-a'> 
                <tr><td>Id Artigo</td> 
                    <td><input type='text' name='id_artigo' size='10' maxlength='10' class='inputForm' value='13'disabled="disabled" /></td> 
                    <td><label class='error' generated='true' for='id_artigo'></label></td></tr><tr><td>Titulo</td> 
                    <td><input type='text' name='titulo' size='50' maxlength='50' class='inputForm' value='Mais uma reportagem' /></td> 
                    <td><label class='error' generated='true' for='titulo'></label></td></tr><tr><td>Destaque</td> 
                    <td><select name='destaque' class='inputForm'>
                            <option value='SIM'  >Sim</option>
                            <option value='NAO' selected >Nao</option>
                        </select></td>
                    <td><label class='error' generated='true' for='destaque'></label></td></tr><tr><td>Conteudo</td> 
                    <td><label class='error' generated='true' for='conteudo'></label></td></tr><tr><td>Data</td>
                    <td><input type='text' name='data' size='10' maxlength='10' class='inputForm' value='2012-06-21' /></td> 
                    <td><label class='error' generated='true' for='data'></label></td></tr><tr><td>Autor</td> 
                    <td><input type='text' name='autor' size='50' maxlength='50' class='inputForm' value='Dra Fátima de Oliveira' /></td> 
                    <td><label class='error' generated='true' for='autor'></label></td></tr><tr><td>Status</td> 
                    <td><select name='status' class='inputForm'>
                            <option value='SIM'  >Sim</option>
                            <option value='NAO' selected >Nao</option>
                        </select></td>
                    <td><label class='error' generated='true' for='status'></label></td></tr><tr><td>&nbsp;</td><td><input value='Salvar'   type='submit' class='inputForm'/>
                        <input value='Cancelar' type='button' class='inputForm' onclick='window.location.href="menu.php?nomeTabela=fat_artigo"'/></td></tr>
            </table>
            <textarea id="editor" rows="10" cols="30" name="conteudo" style="width:100%;height:440px" ></textarea>
        </form>
    </body>
</html>
