<?php
include "config/connection.php";


    $studentID = $_GET['id'];

    // Delete student's subjects
    mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");

    // Delete student's skills
    mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");

    // Delete student's record
    $deleteQuery = "DELETE FROM students WHERE id = '$studentID'";
    if (mysqli_query($connection, $deleteQuery)) {
        echo json_encode(array("status" => "success", "message" => "Data has been deleted successfully."));
    
    } else {
        echo json_encode(array("status" => "error", "message" => "Error deleting data."));
    }

?>
