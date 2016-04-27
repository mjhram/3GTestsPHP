<?php   
	define ('SITE_ROOT', realpath(dirname(__FILE__)));
	ini_set('upload_max_filesize', '100M');
	ini_set('post_max_size', '100M');
	ini_set('max_input_time', 300);
	ini_set('max_execution_time', 300);

	//var_dump($_FILES['ufile']);
	$dest_file = "../files_db/" . basename($_FILES['ufile']['name']);
	//echo "===" . $dest_file;
	if(move_uploaded_file($_FILES['ufile']['tmp_name'], $dest_file)) {
	    echo "success";
	} else{
	    echo "fail";
	}
?>
