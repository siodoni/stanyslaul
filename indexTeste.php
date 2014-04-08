<html>
    <head>
        <title>Stanyslaul</title>
        <script type='text/javascript' src='res/jquery-1.11.0.min.js'></script>
        <script type='text/javascript' src='res/jquery-ui.min.js'></script>
        <script type='text/javascript' src='res/primeui-1.0-min.js'></script>
        <link href='res/primeui-1.0-min.css'    rel='stylesheet'>
        <link href='res/jquery-ui.min.css'      rel='stylesheet'>
        <link href='res/css/primeui.all.css'    rel='stylesheet'>
        
        <link href='res/css/themes/redmond/theme.css' rel='stylesheet'>
        
        <link href='res/css/stanyslaul.all.css' rel='stylesheet'>
    </head>

    <body>
        <form name='form' method='post' action='list.php'>
            <div id="basico" class="st-menu">
                <h1>Cadastro Basico</h1>
                <button id='btn1' type='submit' name='nomeTabela' value='snb_unid_fed' class='menu BAS'>UF</button>
                <button id='btn2' type='submit' name='nomeTabela' value='snb_cidade' class='menu BAS'>Cidade</button>
                <button id='btn3' type='submit' name='nomeTabela' value='snb_tp_logradouro' class='menu BAS'>Tipo de Logradouro</button>
            </div>

            <div id="pessoa" class="st-menu">
                <h1>Cadastro Pessoa/Aluno/Professor</h1>
                <button id='btn4' type='submit' name='nomeTabela' value='snb_tp_pessoa' class='menu PES'>Tipo de Pessoa</button>
                <button id='btn5' type='submit' name='nomeTabela' value='snb_pessoa' class='menu PES'>Pessoa</button>
                <button id='btn6' type='submit' name='nomeTabela' value='snb_filial' class='menu PES'>Filial</button>
                <button id='btn7' type='submit' name='nomeTabela' value='snb_endereco' class='menu PES'>Endereco</button>
                <button id='btn8' type='submit' name='nomeTabela' value='snb_telefone' class='menu PES'>Telefone</button>
                <button id='btn9' type='submit' name='nomeTabela' value='snb_end_eletronico' class='menu PES'>Endereco Eletronico</button>
                <button id='btn10' type='submit' name='nomeTabela' value='snb_aluno' class='menu PES'>Aluno</button>
                <button id='btn11' type='submit' name='nomeTabela' value='snb_professor' class='menu PES'>Professor</button>
            </div>

            <div id="escola" class="st-menu">
                <h1>Controle Escola/Cursos</h1>
                <button id='btn12' type='submit' name='nomeTabela' value='snb_tp_prova' class='menu ESC'>Tipo de Prova</button>
                <button id='btn13' type='submit' name='nomeTabela' value='snb_classe' class='menu ESC'>Classe</button>
                <button id='btn14' type='submit' name='nomeTabela' value='snb_curso' class='menu ESC'>Curso</button>
                <button id='btn15' type='submit' name='nomeTabela' value='snb_curso_periodo' class='menu ESC'>Curso/Periodo</button>
                <button id='btn16' type='submit' name='nomeTabela' value='snb_turma' class='menu ESC'>Turma</button>
                <button id='btn17' type='submit' name='nomeTabela' value='snb_matricula' class='menu ESC'>Matricula</button>
                <button id='btn18' type='submit' name='nomeTabela' value='snb_nota' class='menu ESC'>Nota</button>
                <button id='btn19' type='submit' name='nomeTabela' value='snb_calendario' class='menu ESC'>Calendario</button>
            </div>

            <div id="financeiro" class="st-menu">
                <h1>Financeiro</h1>
                <button id='btn20' type='submit' name='nomeTabela' value='snb_tp_movto_fin' class='menu FIN'>Tipo de Movimento Financeiro</button>
                <button id='btn21' type='submit' name='nomeTabela' value='snb_movto_financeiro' class='menu FIN'>Movimento Financeiro</button>
                <button id='btn22' type='submit' name='nomeTabela' value='snb_boleto' class='menu FIN'>Boleto</button>
            </div>

            <div id="sistema" class="st-menu">
                <h1>Administracao do sistema</h1>
                <button id='btn23' type='submit' name='nomeTabela' value='snb_menu' class='menu SIS'>Menu</button>
                <button id='btn24' type='submit' name='nomeTabela' value='snb_usuario' class='menu SIS'>Usuario</button>
                <button id='btn25' type='submit' name='nomeTabela' value='snb_autorizacao' class='menu SIS'>Autorizacao ao Sistema</button>
            </div>
        </form>
        <script type='text/javascript'>
            $(function() {
                $('#btn1').puibutton();
                $('#btn2').puibutton();
                $('#btn3').puibutton();
                $('#btn4').puibutton();
                $('#btn5').puibutton();
                $('#btn6').puibutton();
                $('#btn7').puibutton();
                $('#btn8').puibutton();
                $('#btn9').puibutton();
                $('#btn10').puibutton();
                $('#btn11').puibutton();
                $('#btn12').puibutton();
                $('#btn13').puibutton();
                $('#btn14').puibutton();
                $('#btn15').puibutton();
                $('#btn16').puibutton();
                $('#btn17').puibutton();
                $('#btn18').puibutton();
                $('#btn19').puibutton();
                $('#btn20').puibutton();
                $('#btn21').puibutton();
                $('#btn22').puibutton();
                $('#btn23').puibutton();
                $('#btn24').puibutton();
                $('#btn25').puibutton();
            });
        </script>
    </body>
</html>