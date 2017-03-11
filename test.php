<?php
    require 'connect.php';

{
    define ("site_name",'test.php');
    $res = "failed";
    $aSql = "INSERT INTO log(user, details, result, ip,url,sitename) VALUES(";
    if(!$_SESSION['usr']) {
        $aSql .= "-1,";
    } else {
        $aSql .= "{$_SESSION['id']},";
    }
    $aSql .= "'addTest', {$res}, '{$_SERVER['REMOTE_ADDR']}','{$_SERVER['REQUEST_URI']}','".site_name."')";
    mysqli_query($GLOBALS["___mysqli_ston"], $aSql);
    echo $aSql;
}
?>