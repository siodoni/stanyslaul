<?php
//Obrigatorio habilitar a extensão php_fileinfo.dll
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$img_file = 'common/topo.png';
$imgData = base64_encode(file_get_contents($img_file));
$src = 'data:'.finfo_file($finfo, $img_file).';base64,'.$imgData;
echo '<html><body><img src="'.$src.'">'.$src.'</body></html>';
finfo_close($finfo);