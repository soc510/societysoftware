<?php  
require_once(APP . 'Vendor' . DS . 'dompdf' . DS . 'dompdf_config.inc.php'); 
spl_autoload_register('DOMPDF_autoload'); 
$dompdf = new DOMPDF();
//$dompdf->orientation = 'landscape'; 
//$dompdf->set_paper = 'A4';
$paper_size = 'A4';
//$orientation = 'landscape';
$dompdf->set_paper($paper_size);
//print_r($content_for_layout);die;
$dompdf->load_html(utf8_decode($content_for_layout),Configure::read('App.encoding'));
$dompdf->render();
echo $dompdf->output();
?>