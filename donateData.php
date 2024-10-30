<?php 
	global $wpdb;
	
	$donateFinalURL='';
    $donateFinalURL = 'https://www.idonate.ie/donation_widget/register-donor-anonymous.php?pid=';	
	$charityId = $_GET['charityId'];	
	$vatInfo = $_GET['tax'];
	$fixed_amount = $_GET['amount'];
	$fmgy = $_GET['type'];
	
		
	$donateFinalURL .= $charityId;
	if($vatInfo == 'no'){
		$donateFinalURL .= "&tax=".$vatInfo;
	}
	if(!empty($fixed_amount)){
		$donateFinalURL .= "&amount=".$fixed_amount;
	}
	if($fmgy=='yes'){
		$donateFinalURL .= "&type=monthly";
	}
	
	//$homepage = file_get_contents($donateFinalURL); 
	
	/*======*/
	
echo $donateFinalURL;


?>