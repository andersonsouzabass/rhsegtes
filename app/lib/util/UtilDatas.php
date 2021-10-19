<?php
class UtilDatas
{
    /**
     * Class Constructor
     */
    static function dataextenso($dataext="")
    {
        setlocale(LC_TIME, 'portuguese'); 
        date_default_timezone_set('America/Sao_Paulo'); 
        if (!isset($dataext)||trim($dataext)==""){    
            $dataext = date('Y-m-d');
        }
        return strftime("%d de %B de %Y", strtotime($dataext));
        //new TMessage('info',strftime("%d de %B de %Y", strtotime($dataext)));
        
    }
    
}
// Utilização
//UtilDatas::dataextenso('16-01-10');
// resultado : 26 de abril de 2016
// Se passar sem parametro , usa data de hoje
?>