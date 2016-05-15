<?php
    require 'connect.php';
    $sSql = "SELECT * FROM 3gTests";
    $rs_result = mysqli_query($GLOBALS["___mysqli_ston"], $sSql);
    if($rs_result) {
        $total_records = mysqli_num_rows($rs_result);
        echo "--->";
        echo $total_records;
    }
    echo " 0-------------------------->";
    echo $rs_result;
		print_r($rs_result);
echo("Error description2: " . mysqli_error($GLOBALS["___mysqli_ston"]));
?>