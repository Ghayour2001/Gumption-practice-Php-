<?php
session_start();
include 'config/connection.php';
    $studentId = $_REQUEST['id'];
    //   echo $studentId;
    $deleteCityQuery = mysqli_query($connection, "DELETE FROM studentcity WHERE fkstudentid = '$studentId'");

    if ($deleteCityQuery) 
    {
        $deletesubject = mysqli_query($connection, "DELETE FROM studentsubject WHERE fkstudentid = '$studentId'");
        $deleteStudentQuery = mysqli_query($connection, "DELETE FROM student WHERE studentid = '$studentId'");

        if ($deleteStudentQuery && $deletesubject) 
        {
            $_SESSION['toastr'] = array(
                'type' => 'success',
                'message' => 'Student Deleted!',
                'title' => 'Success!'
            );
        } 
        else 
        {
            $_SESSION['toastr'] = array(
                'type' => 'error',
                'message' => 'Failed to delete student data!',
                'title' => 'Error!'
            );
        }
    } 
    else 
    {
        $_SESSION['toastr'] = array(
            'type' => 'error',
            'message' => 'Failed to delete studentcity data!',
            'title' => 'Error!'
        );
    }

    header('location: index.php'); 


?>