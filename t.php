<?php
/**
 * Created by PhpStorm.
 * User: mohammad.haider
 * Date: 7/20/2016
 * Time: 3:48 PM
 */


error_reporting(E_ALL ^ E_NOTICE);
session_name('aTTS');
session_start();
if($_SESSION['ar_en']==1) {
    header('location: en/index.php');
} else {
    header('location: en/index.php');
}
?>