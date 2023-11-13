<?php
session_start();
include 'config/connection.php';

$subjectId = $_POST['subjectId'];

$sql = "DELETE FROM studentsubject WHERE studentsubjectid = '$subjectId'";
$result = mysqli_query($connection, $sql);

if ($result) {
    $_SESSION['toastr'] = array(
        'type'    => 'success', 
        'message' => 'Subject Deleted !',
        'title'   => 'Success!'
    );
} else {
    $_SESSION['toastr'] = array(
        'type'    => 'error', 
        'message' => 'Failed to Subject Deleted!',
        'title'   => 'Error!'
    );
}
?>
