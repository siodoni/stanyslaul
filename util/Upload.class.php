<?php

class Upload {

    private $nomeFinal;
    private $pasta;
    private $msgErro = "";

    /**
     * @method inserir arquivo
     * @param arquivo, nome do campo, pasta, renomeia
     * @return true se o arquivo foi inserido
     */
    public function inserir($arquivo, $nomeCampo, $pasta, $renomeia) {

        // arquivo passado por parametro
        $_FILES['$nomeCampo'] = $arquivo;
        $fName = $_FILES["$nomeCampo"]['name'];

        $_UP['tamanho']  = Config::FILE_SIZE;                    //Tamanho máximo do arquivo (em Bytes)
        $_UP['extensao'] = explode(",", Config::FILE_EXTENSION); // Array com as extensões permitidas
        $_UP['renomeia'] = $renomeia;                            // Renomeia o arquivo? (Se true, o arquivo será salvo com a hora do sistema mais a extensão).

        if ($pasta == "") {
            $_UP['pasta'] = Config::FILE_FOLDER;
        } else {
            $_UP['pasta'] = $pasta;
        }

        $this->pasta = $_UP['pasta'];

        // Array com os tipos de erros de upload do PHP
        $_UP['erros'][0] = 'N&atilde;o houve erro';
        $_UP['erros'][1] = 'O arquivo no upload &eacute; maior do que o limite do PHP';
        $_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
        $_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
        $_UP['erros'][4] = 'N&atilde;o foi feito o upload do arquivo';
        $_UP['erros'][5] = 'Arquivo j&aacute; existe no servidor';

        // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
        if ($_FILES["$nomeCampo"]['error'] != 0) {
            $this->msgErro .= "N&atilde;o foi poss&iacute;vel fazer o upload, erro:<br />" . $_UP['erros'][$_FILES["$nomeCampo"]['error']];
            return false;
        }

        // Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
        // Faz a verificação da extensão do arquivo
        $extensaoArray = explode(".", $fName);
        $extensao = strtolower(end($extensaoArray));

        if (array_search($extensao, $_UP['extensao']) === false) {
            $this->msgErro .= "Por favor, envie arquivos com as seguintes extens&otilde;es: " . json_encode($_UP['extensao']);
            return false;
        }

        if ($_FILES["$nomeCampo"]['size'] > $_UP['tamanho']) {
            $this->msgErro .= "O arquivo enviado &eacute; muito grande (" . round($_FILES["$nomeCampo"]['size'] / 1024, 0) . "Kb), envie arquivos de at&eacute; " . ($_UP['tamanho'] / 1024) . "Kb.";
            return false;
        }

        $this->nomeFinal = str_replace(" ", "_", $fName);

        // teste para verificar se o arquivo existe
        if (file_exists($_UP['pasta'] . $this->nomeFinal)) {
            $this->msgErro .= "Erro " . $_UP['erros'][5];
            return false;
        }

        if ($_UP['renomeia'] == true) {
            //Cria um nome baseado no UNIX TIMESTAMP atual e com extensão do arquivo
            $this->nomeFinal = time() . '.' . $extensao;
        }

        // Depois verifica se é possível mover o arquivo para a pasta escolhida
        if (move_uploaded_file($_FILES["$nomeCampo"]['tmp_name'], $_UP['pasta'] . $this->nomeFinal)) {
            $this->createThumbs($this->nomeFinal,$_UP['pasta'], $_UP['pasta'], 200);
            $this->msgErro .= "Upload efetuado com sucesso!";
            return true;
        } else {
            $this->msgErro .= "N&atilde;o foi poss&iacute;vel enviar o arquivo, tente novamente";
            $this->msgErro .= "Erro " . $_UP['erro'][$_FILES["$nomeCampo"]['error']];
            return false;
        }
    }

    public function getNomeFinal() {
        return $this->nomeFinal;
    }

    public function getMsgErro() {
        return $this->msgErro;
    }

    private function createThumbs($fname, $pathToImages, $pathToThumbs, $thumbWidth) {
        // parse path for the extension
        $info = pathinfo($pathToImages.$fname);
        // continue only if this is a JPEG image
        if (strtolower($info['extension']) == 'jpg'
        ||  strtolower($info['extension']) == 'png') {
            // load image and get image size
            if (strtolower($info['extension']) == 'jpg'){
                $img = imagecreatefromjpeg("{$pathToImages}{$fname}");
            } else if (strtolower($info['extension']) == 'png'){
                $img = imagecreatefrompng("{$pathToImages}{$fname}");
            }
            $width = imagesx($img);
            $height = imagesy($img);
            // calculate thumbnail size
            $new_width = $thumbWidth;
            $new_height = floor($height * ( $thumbWidth / $width ));
            // create a new temporary image
            $tmp_img = imagecreatetruecolor($new_width, $new_height);
            // copy and resize old image into new image 
            imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            // save thumbnail into a file
            if (strtolower($info['extension']) == 'jpg'){
                imagejpeg($tmp_img, "{$pathToThumbs}th{$fname}");
            } else if (strtolower($info['extension']) == 'png'){
                imagepng($tmp_img, "{$pathToThumbs}th{$fname}");
            }
        }
        return true;
    }
}
