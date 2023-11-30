<?php

if(isset($_GET['d']) && isset($_GET['f'] )){
    $d = $_GET['d'];
    $f = $_GET['f'];
    $t = $_GET['t'];
}
else 
    exit;

include('config.php');
require_once("classes/api.php");

require_once('vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()
    ->setTitle('KIBO DEPOT')
    ->setSubject('Mvt de sorties et d entrées');

$api   		= new API();
$api->link  = $link;
$api->url 	= $url;
$api->key 	= $key;
$c          = "C";
$txt        = "Sorties";

if($t == "0"){
    $mvt  = $api->getSorties($d,$f);
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Date')
    ->setCellValue('B1', 'N°');

    $i   = 2;
    foreach ($mvt as $value) {
        $spreadsheet->getActiveSheet()
        ->setCellValue("A".$i, $value["sortie_date"]->format("d/m/Y"))
        ->setCellValue("B".$i, $value["num"]);
        $i++;
    }
}
else{
    $mvt  = $api->getRetours($d,$f);
    $spreadsheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'Date')
    ->setCellValue('B1', 'N°')
    ->setCellValue('C1', 'Article')
    ->setCellValue('D1', 'Désignation')
    ->setCellValue('E1', 'Quantité')
    ->setCellValue('F1', 'Quantité retournée');

    $i   = 2;
    foreach ($mvt as $value) {
        $spreadsheet->getActiveSheet()
        ->setCellValue("A".$i, $value["dates"]->format("d/m/Y"))
        ->setCellValue("B".$i, $value["num"])
        ->setCellValue("C".$i, $value["article"])
        ->setCellValue("D".$i, $value["designation"])
        ->setCellValue("E".$i, $value["qte"])
        ->setCellValue("F".$i, $value["qte_retour"]);
        $i++;
    }
    $c      = "G";
    $txt    = "Retours";
}





$styleArray = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];

$spreadsheet->getActiveSheet()->getStyle('1:1')->applyFromArray($styleArray);

for($col = 'A';$col<$c;$col++){
    $spreadsheet->getActiveSheet()->getColumnDimension($col)->setAutoSize(true);
}

$spreadsheet->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
$spreadsheet->getActiveSheet()->getPageSetup()
    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$filename   = "Mvt ".$txt." du ".date("d/m/Y",strtotime($d))." au ".date("d/m/Y",strtotime($f))." ".time();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
header('Cache-Control: max-age=0');

$writer     = IOFactory::createWriter($spreadsheet, "Xlsx"); 
$writer->save('php://output');