<?php
include 'vendor/autoload.php';

$connect = new PDO("mysql:host=localhost;dbname= ci_crud","root","");

if($_FILES["import_excel"]["name"]!=''){
    $allowed_extension = array('xls','csv');
}else{
    $message= '<div></div>';
}


?>