<?php
if (isset($_POST["Import"])) {
	$filename = $_FILES["file"]["tmp_name"];
	if ($_FILES["file"]["size"] > 0) {
		
		$convert_type = $_POST['convert_type'];
		if($convert_type == 'ebay'){
			$result_array = convert_to_ebay($filename);
		}else{
			$result_array = convert_to_other($filename);
		}

		

		export_csv($result_array, $convert_type);		
	}
}

// if(isset($_POST["Export"])){

function convert_to_ebay($filename){
	$file = fopen($filename, "r");
	$flag = true;
	$counter = 0;
	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {		

		if ($flag) {
			$flag = false;
			continue;
		}
		$getebayData = array($getData[0],$getData[2],$getData[1],$getData[4],$getData[3]);

		$result_array[$counter] = $getebayData;
		$counter++;		
	}
	fclose($file);

	return $result_array;
}

function convert_to_other($filename){
	$file = fopen($filename, "r");
	$flag = true;
	$counter = 0;
	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {		

		if ($flag) {
			$flag = false;
			continue;
		}

		$result_array[$counter] = $getData;
		$counter++;		
	}
	fclose($file);

	return $result_array;
}

function export_csv($result_array, $convert_type)
{
	header('Content-Type: text/csv; charset=utf-8');	

	if($convert_type == 'ebay'){
		header('Content-Disposition: attachment; filename='.$convert_type.'.csv');	
		$header_array = array(
		'ID',
		'Last Name',
		'First Name',
		'Joining Date',
		'Email'
		);
	}else{
		header('Content-Disposition: attachment; filename='.$convert_type.'.csv');	
		$header_array = array(
		'ID',
		'First Name',
		'Last Name',
		'Email',
		'Joining Date'
		);
	}

	$output = fopen("php://output", "w");
	fputcsv($output, $header_array);
	if (count($result_array) > 0) {
		foreach($result_array as $key => $row) {
			fputcsv($output, $row);
		}
	}

	fclose($output);
}
?>
