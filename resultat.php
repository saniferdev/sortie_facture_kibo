<?php

include('config.php');

require_once("classes/api.php");

$api   		  = new API();
$api->link  = $link;
$api->url 	= $url;
$api->key 	= $key;

$numero     = $date = $data = $num = "";
if(isset($_POST['num']) && !empty($_POST['num'])){
  $num      = $_POST['num'];
  $data     = $api->getData($url,$key,$num);

  foreach ($data as $value) {
    $numero   = $value->receipt_number;
  } 
  if(!isset($numero) && empty($numero)) echo "";
  else echo json_encode($data);
}
elseif(isset($_POST['factR']) && !empty($_POST['factR'])){
  $num      = $_POST['factR'];
  $data     = $api->getData($url,$key,$num);

  foreach ($data as $value) {
    $numero   = $value->receipt_number;
  } 
  if(!isset($numero) && empty($numero)) echo "";
  else echo json_encode($data);
}
elseif(isset($_POST['fact']) && !empty($_POST['fact'])){
  $num      = $_POST['fact'];
  $data     = $api->getDataNum($num);

  if(isset($data) && $data == $num){
    echo "Le ticket ou la facture a été bien enregistré pour une sortie";
  }
  else if(isset($data) && $data == "1"){
    $donnee     = $api->getDate($num);
    foreach ($donnee as $value) {
      $dat = $value["sortie_date"];
    }
    $date = $dat->format("d-m-Y H:i:s");
    echo "Le ticket ou la facture a été déjà sortie le ".$date;
  }
  else "Veuillez réessayer svp!";
}
else if( isset($_POST["add"]) && !empty($_POST["add"]) ){
  $num      = $_POST['facture'];
  $array    = $_POST["add"];
  $output   = $api->InsertRetour($num,$array);  
  
  if(isset($output) && $output == $num){
    echo "La facture a été bien rétournée..";
  }
  else
    echo "Désolé, un problème est survenu. Veuillez réessayer plus tard.";
}
else exit;

?>