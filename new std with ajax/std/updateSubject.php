
<?php
session_start();
include 'config/connection.php';
$subjectId = $_POST['subjectId']; // Retrieve the subjectId from the POST data
$subjectName = $_POST['subjectName'];
$marks = $_POST['marks'];

$sql = "UPDATE studentsubject SET subjectName = '$subjectName', marks = '$marks' WHERE studentsubjectid = '$subjectId'";
$result = mysqli_query($connection, $sql);

if ($result) {
    // Send a success response if the update was successful
    // echo "Subject updated successfully";
    $_SESSION['toastr'] = array(
        'type'    => 'success', 
        'message' => 'Subject Updated!',
        'title'   => 'Success!'
    );
} else {
    $_SESSION['toastr'] = array(
        'type'    => 'error', 
        'message' => 'Failed to Update Subject!',
        'title'   => 'Error!'
    );
}


?>
