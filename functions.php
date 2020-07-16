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
                        $test = array('TIPO' => "TIPO",'DATA' => "DATA", 'VALOR' => "VALOR", 'DESCRIÇÃO' => "DESCRIÇÃO");
                        fputcsv($f, $test ,';','"');
                    }

                    $val = NULL;
                    if($put_arr[0] == "TRNTYPE")
                        $tipo  =  $put_arr[1];
                    if($put_arr[0] == "DTPOSTED")
                    {
                        $data  =  $put_arr[1]->__toString();
                        // $data   =  $data->__toString();
                        $date   =  "00000000";
                        var_dump($data);
                        printf("<br>");
                        var_dump($date);

                        //20180429120000[-3:BRT]
                        for( $i = 0; $i < 9; $i++)
                        {
                            if($i < 5)
                                $date[$i] = $data[$i];
                            else
                                if($i == 5)
                                    $date[$i] = '-';
                            if($i < 7)
                                $date[$i] = $data[$i];
                            else
                                if ($i == 7)
                                    $date[$i] = '-';
                            if($i < 9)
                                $date[$i] = $data[$i];

                            printf("<br>");
                            var_dump($date);
                        }
                        
                    }
                        
                    if($put_arr[0] == "TRNAMT")
                        $valor = $put_arr[1];
                    if($put_arr[0] == "MEMO")
                        $val  =  array('tipo' => $tipo,'data' => $data,'valor' => $valor,'desc' => $put_arr[1]);
                    if($val != NULL)
                    {
                        fputcsv($f, $val ,';','"');
                    }
                }else
                    createCsv($item, $f);
            }
        }
        ?>