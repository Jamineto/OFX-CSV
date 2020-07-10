<?php
include 'functions.php';

//Diretorios
$target_dir = __DIR__ . '/arquivos/';
$down_dir   = __DIR__ . '/downloads/';

$dir = '/zips/arquivoUsuariox/';

//Quantidade de arquivos upados
$file_count = count($_FILES['arquivo']['name']);

$uploadOk = 1;

//ZIP
$zipname = "csv_arquivos.zip";
$zippath = __DIR__ . '/' . $dir . $zipname;
$fullPath  = $down_dir.'/'.$zipname;

//Scanear e pular padroes do diretorio
$scanDir = scandir($down_dir);
array_shift($scanDir);
array_shift($scanDir);

//Verificar se existe pasta do usuario
if(!file_exists($down_dir . $dir))
    mkdir(__DIR__ . $dir);


//Cria ZIP
$zip = new ZipArchive();
if ($zip->open($zippath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
    die ("Ocorreu um erro ao criar seu arquivo ZIP.");
  }

//Laço para cada arquivo
for($i=0;$i<$file_count;$i++)
{
    $FileType = strtolower(pathinfo($_FILES['arquivo']['name'][$i],PATHINFO_EXTENSION));
    $target_file = $target_dir . basename($_FILES["arquivo"]["name"][$i]);
// Verificar tamanho
if ($_FILES["arquivo"]["size"][$i] > 500000) {
    echo "Desculpe, seu arquivo é muito grande.";
    $uploadOk = 0;
}else

// Verificar se existe algum erro
if ($uploadOk == 0) {
    echo "Desculpe, algum erro aconteceu ao fazer upload.\n";
// Tentar fazer o upload
} else {
    if (move_uploaded_file($_FILES["arquivo"]["tmp_name"][$i], $target_file)) {
        
        $source = fopen($target_file, 'r+');
        $headers = array();
        $charsets = array(
            1252 => 'WINDOWS-1251',
        );

        //cabecalho padrao para ofx
                $headers = array( 
                "OFXHEADER"=>100,
                "DATA"=>"OFXSGML",
                "VERSION"=>102,
                "SECURITY"=>"NONE",
                "ENCODING"=>"USASCII",
                "CHARSET"=>1252,
                "COMPRESSION"=>"NONE",
                "OLDFILEUID"=>"NONE",
                "NEWFILEUID"=>"NONE");

        $buffer = '';
        

        while(!feof($source)) {

            //Pula linha ate comecar as informacoes
            $line = trim(fgets($source));
            if ($line === '') {
                continue;
            }
            
            //Fecha tags
            if($line[0] == '<')
            {
                $line = iconv($charsets[$headers['CHARSET']], 'UTF-8', $line);
                if (substr($line, -1, 1) !== '>') {
                    list($tag) = explode('>', $line, 2);
                    $line .= '</' . substr($tag, 1) . '>';
                    
                }
                
                $buffer .= $line ."\n";
            }

            
        }
        
        $doc = new DOMDocument();
        $doc->recover = true;
        $doc->preserveWhiteSpace = true;
        $doc->formatOutput = true;
        $save = libxml_use_internal_errors(true);
        $doc->loadXML($buffer);
        libxml_use_internal_errors($save);

        $name = $target_file;
        $name = explode("/",$name);
        $name = explode(".",$name[1]);
        $name = $name[0];
        $arquivo = fopen($target_dir . $name . ".xml","w");
        $file =  $doc->saveXML();
        fwrite($arquivo,$file);
        fclose($arquivo);

        $filexml=$target_dir . $name . ".xml";


        
        

        if (file_exists($filexml)) 
        {
            $xml = simplexml_load_file($filexml);
            $f = fopen($down_dir . $name . '.csv', 'w');
            createCsv($xml, $f);
            fclose($f);
        }

        $filename = $down_dir . $name . '.csv';
        
        
         
        
        //  header('HTTP/1.1 200 OK');
        //  header('Cache-Control: no-cache, must-revalidate');
        //  header("Pragma: no-cache");
        //  header("Expires: 0");
        //  header("Content-type: text/csv");
        //  header("Content-Disposition: attachment; filename=$name");
        //  readfile('downloads/' . $name . '.csv');

    }else
        echo "Desculpe, algum erro aconteceu.<br>";
}

}

if( $zip->open($zippath, ZipArchive::CREATE) )
    foreach($scanDir as $file)
        $zip->addFile($down_dir.'/'.$file, $file);
$zip->close();


//Download do ZIP.
// if(file_exists($zippath)){
//     header('Content-Type: application/zip');
//     header('Content-Disposition: attachment; filename="'.$zipname.'"');
//     readfile($zippath);
//     //Excluir ZIP.
//     //unlink($filename);
// }

$user_premium = true;
?>
<style>
table, th, td {
  border: 1px solid black;
}
</style>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <title>Download CSV ou ZIP</title>
</head>
<body>
<table>
<tr>
    <td>
        Nome do arquivo
    </td>
    <td>
        Tamanho
    </td>
    <td>
        Status
    </td>
</tr>
    <?php 
        foreach($scanDir as $file)
        {
            $digitos = 2;
            $arquivoTamanho = filesize($down_dir . '/' . $file);
            $tamanhos = array("TB","GB","MB","KB","B");
            $total = count($tamanhos);
            while ($total-- && $arquivoTamanho > 1024) {
                $arquivoTamanho /= 1024;
            }
            echo "
            <tr>
                <td>
                    <a href= /downloads/" . $file ."> " . strtolower($file) . " </a>
                </td>
                <td>
                    " ;
                    echo round($arquivoTamanho, $digitos) . " " . $tamanhos[$total] . "
                </td>
                <td>
                    "; if(!$arquivoTamanho == 0)
                        printf("Ok");
                      else
                        printf("Erro");
                    echo "
                </td>
            </tr>";
            
        }
        if($user_premium == true)
        echo "
        <tr>
            <td>
                Download ZIP
            </td>
        </tr>
        
        ";
    ?>
</table>
</body>
</html>