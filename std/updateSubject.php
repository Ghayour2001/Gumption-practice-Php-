
<?php
session_start();
include 'config/connection.php';
$studentsubjectid = $_POST['studentsubjectid']; 
$pksubjectid = $_POST['pksubjectid'];
$marks = $_POST['marks'];
$totalmarks = $_POST['totalmarks'];

$sql = "UPDATE studentsubject SET fksubjectid = '$pksubjectid', marks = '$marks',totalmarks='$totalmarks' WHERE studentsubjectid = '$studentsubjectid'";
$result = mysqli_query($connection, $sql);

if ($result)
{
    $_SESSION['toastr'] = array(
        'type'    => 'success', 
        'message' => 'Subject Updated!',
        'title'   => 'Success!'
    );
} 
else 
{
    $_SESSION['toastr'] = array(
        'type'    => 'error', 
        'message' => 'Failed to Update Subject!',
        'title'   => 'Error!'
    );
}


?>
