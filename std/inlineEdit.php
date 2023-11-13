<?php
include 'config/connection.php';
// print_r($_POST);
// exit ;
$column         =   $_POST["column"];
$value          =   $_POST["value"];
$id             =   $_POST["studentsubjectid"];
$sql            =   "UPDATE  studentsubject set ".$_REQUEST["column"]."='".$_REQUEST["value"]."' WHERE  studentsubjectid ='".$_REQUEST["studentsubjectid"]."'";
$updatesubject  =    mysqli_query($connection, $sql) or die("database error:". mysqli_error($connection));
if($updatesubject)
{
echo 'Subject Updated';
}
?>