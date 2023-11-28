<?php

Class API {
    public $link;
    public $url;
    public $key;


    public function getRest($url,$key,$num){

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "Receipts":[
                {
                    "receipt_number" : [
                        {
                            "invoice_num":"'.$num.'"
                        }
                    ]
                }
            ]
          }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Token: '.$key
          ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;

    }

    public function getData($url,$key,$num){
      $rest     = $this->getRest($url,$key,$num);
      $data     = json_decode($rest);
      $donne    = $data->response->data;
      $array    = array();
      foreach ($donne as $value) {
        $array[] = $value;
      }
      return $array;
    }

    public function getDataNum($num){
        $queryParams    = $data = array();
        $queryOptions   = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query          = " SELECT num FROM historique_facture WHERE num = '".$num."' ";

        $resultat       = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
            var_dump(sqlsrv_errors());
            return false;
        } elseif (sqlsrv_num_rows($resultat) == 0) {
            $this->InsertData($num);
            return $num;
        } else {
            $this->InsertDataDuplicat($num);
            return 1;
        }
    }

    public function getDate($num){
        $queryParams    = $data = array();
        $queryOptions   = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $query          = " SELECT sortie_date FROM historique_facture WHERE num = '".$num."' ";

        $resultat       = sqlsrv_query($this->link, $query, $queryParams, $queryOptions);
        if ($resultat == FALSE) {
            var_dump(sqlsrv_errors());
            return false;
        } else {
            while($row = sqlsrv_fetch_array($resultat, SQLSRV_FETCH_ASSOC)){
                $data[] = $row;
            }
            return $data;
        }
    }

    public function InsertData($n){
        $query = "INSERT INTO [dbo].[historique_facture]
                       ([sortie_date]
                       ,[num])
                 VALUES
                       (GETDATE()
                       ,'".$n."')";

        sqlsrv_query($this->link, $query);
        
    }

    public function InsertDataDuplicat($n){
        $query = "INSERT INTO [dbo].[duplicat_validation] ([num]) VALUES ('".$n."')";
        sqlsrv_query($this->link, $query);
    }

    public function InsertRetour($num,$array){
        $m   = $val = "";
        $end = end($array);
        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if($value == $end){
                    $m = ";";
                }
                else{
                    $m = ",";
                }
                $exp   = explode('-----',$value);
                $ref   = $key;
                $des   = $exp[0];
                $qte   = $exp[1];
                $qteR  = $exp[2];
                $val   .= "('".$num."','".$ref."','".str_replace("'", " ", $des)."','".$qte."','".$qteR."')".$m;
            }
        }

        $query = "INSERT INTO [dbo].[retour_validation]
                       ([num]
                       ,[article]
                       ,[designation]
                       ,[qte]
                       ,[qte_retour])
                 VALUES ".$val;

        if(sqlsrv_query($this->link, $query)) return $num;
    }

}
?>