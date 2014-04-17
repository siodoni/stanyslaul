<?php

class Upload {

    function inserir($arquivo, $nomeCampo, $pasta) {

        /**
         * @method inserir arquivo
         * @param arquivo, nome do campo
         * @return true se o arquivo foi inserido
         */
        // arquivo passado por parametro
        $_FILES['$nomeCampo'] = $arquivo;
        $f_name = $_FILES["$nomeCampo"]['name'];

        // Tamanho máximo do arquivo (em Bytes)
        $_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb
        // Array com as extensões permitidas
        $_UP['extensao'] = array(0 => 'pdf', 1 => 'php', 2 => 'jpg');
        // Renomeia o arquivo? (Se true, o arquivo será salvo como .pdf e um nome único)
        $_UP['renomeia'] = false;
        if ($pasta == "") {
            $_UP['pasta'] = "../publicacao/arquivos/";
        } else {
            $_UP['pasta'] = $pasta;
        }

        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'Não houve erro';
        $_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'Não foi feito o upload do arquivo';
        $_UP['erros'][5] = 'Arquivo já existe no servidor';

        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES["$nomeCampo"]['error'] != 0) {
            return ("Não foi possível fazer o upload, erro:<br />" . $_UP['erros'][$_FILES['$nomeCampo']['error']]);
            //exit; // Para a execução do script
        }

        // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
        // Faz a verificação da extensão do arquivo
        $extensao_arr = explode(".", $f_name);
        $extensao = strtolower(end($extensao_arr));
        if (array_search($extensao, $_UP['extensao']) === false) {
            return "Por favor, envie arquivos com as seguintes extensões: pdf.";
        } else if ($_UP['tamanho'] < $_FILES['$nomeCampo']['size']) {
            return "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
        } else {
            $nome_final = str_replace(" ", "_", $f_name);
            // teste para verificar se o arquivo existe
            if (file_exists($_UP['pasta'] . $nome_final)) {
                return "Erro " . $_UP['erros'][5];
                //$_UP['renomeia'] = true;
            }

            if ($_UP['renomeia'] == true) {
            // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .jpg
                $nome_final = time() . '_' . $f_name . '.' . $extensao;
            }
            // Depois verifica se é possível mover o arquivo para a pasta escolhida
            if (move_uploaded_file($_FILES["$nomeCampo"]['tmp_name'], $_UP['pasta'] . $nome_final)) {
            // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
                //echo "Upload efetuado com sucesso!";
                return true;
            } else {
            // Não foi possível fazer o upload, provavelmente a pasta está incorreta
                //echo "Não foi possível enviar o arquivo, tente novamente";
                return "Erro " . $_UP['erro'][$_FILES["$nomeCampo"]['error']];
            }
        }
    }

}

?>