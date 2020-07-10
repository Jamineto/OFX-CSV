<?php
function createCsv($xml,$f)
        {
            foreach ($xml->children() as $item) 
            {

                $hasChild = (count($item->children()) > 0) ? true : false;

                if( ! $hasChild)
                {
                    $put_arr = array($item->getName(),$item); 
                
                    if($put_arr[0] == "DTSERVER")
                    {
                        $arra =  array("Data Server" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "ORG")
                    {
                        $arra =  array("Banco" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "BANKID")
                    {
                        $arra =  array("Identificação Banco" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "ACCTID")
                    {
                        $arra =  array("Conta" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "ACCTTYPE")
                    {
                        $arra =  array("Tipo de Conta" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "DTSTART")
                    {
                        $arra =  array("Data Inicio" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }else
                        if($put_arr[0] == "DTEND")
                    {
                        $arra =  array("Data Fim" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }
                    if($put_arr[0] == "BALAMT")
                    {
                        $arra =  array("Saldo" , $put_arr[1]);
                        fputcsv($f, $arra ,';','"');
                    }


                    if($put_arr[0] == "DTEND")
                    {
                        $test = array('DATA' => "DATA", 'VALOR' => "VALOR", 'DESCRIÇÃO' => "DESCRIÇÃO");
                        fputcsv($f, $test ,';','"');
                    }

                    $val = NULL;
                    if($put_arr[0] == "DTPOSTED")
                        $data  =  $put_arr[1];
                    if($put_arr[0] == "TRNAMT")
                        $valor = $put_arr[1];
                    if($put_arr[0] == "MEMO")
                        $val  =  array('data' => $data,'valor' => $valor,'desc' => $put_arr[1]);
                    if($val != NULL)
                    {
                        fputcsv($f, $val ,';','"');
                    }
                }else
                    createCsv($item, $f);
            }
        }
        ?>