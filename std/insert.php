<?php
session_start();
include 'config/connection.php';
if (isset($_POST['save'])) 
{
    // echo '<pre>';
    // print_r($_POST);
    // echo '</pre>';

    $name         =   $_POST['name'];
    $gender       =   $_POST['gender'];
    if (isset($_POST['gameId'])) 
    {
        $game        = $_POST['gameId'];
        $gameid      =   implode(',', $game);
    } 
    else 
    {
        $gameid = null;
    }
    if (!empty($_FILES['image']['name'])) 
    {
        $imagePath  = 'images/' . time() . $_FILES['image']['name'];
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $image = $imagePath;
        } 
        else 
        {
            $image = null;
        }
    } 
    else 
    {
        $image = null;
    }
    $insertstudent      =   mysqli_query($connection, "INSERT INTO student (studentname, gender,fkgameid,image) VALUES ('$name', '$gender','$gameid','$image')");
    if ($insertstudent !== TRUE) 
    {
        $_SESSION['toastr'] = array(
            'type'    => 'error',
            'message' => 'Something wrong Insert student!',
            'title'   => 'error!'
        );
    }
    if ($insertstudent) 
    {
        $studentid      =   mysqli_insert_id($connection);
        $pksubjectids = implode(',', $_SESSION['pksubjectid']);

        $updateQuery = "UPDATE `studentsubject` SET `fkstudentid`='$studentid' WHERE studentsubjectid IN ($pksubjectids)";
        $updateSubject = mysqli_query($connection, $updateQuery);
        unset($_SESSION['pksubjectid']);
        unset($_SESSION['studentID']);
    }
    // Insert 'studentcity' 
    $cities        =   $_POST['city'];
    $provinces     =   $_POST['province'];
    for ($i = 0; $i < count($cities); $i++) 
    {
        $city      = mysqli_real_escape_string($connection, $cities[$i]);
        $province  =  mysqli_real_escape_string($connection, $provinces[$i]);
        $sql_city = mysqli_query($connection, "INSERT INTO studentcity (fkstudentid, city, province) VALUES ('$studentid', '$city', '$province')");
        if ($sql_city == TRUE) {
            $_SESSION['toastr'] = array(
                'type'    => 'error',
                'message' => 'Error inserting studentcity data: ' . mysqli_error($connection),
                'title'   => 'Error!'
            );
        }
    }



    if (($insertstudent && $updateQuery)) 
    {
        $_SESSION['toastr'] = array(
            'type'    => 'success',
            'message' => 'Data Saved !',
            'title'   => 'Success!'
        );
        header('location: index.php');
    } 
    else 
    {
        $_SESSION['toastr'] = array(
            'type'    => 'error',
            'message' => 'Something wrong Insert student!',
            'title'   => 'error!'
        );
    }
}


if (isset($_POST['update'])) 
{
    //   echo '<pre>';
    //   print_r($_POST);
    // echo '</pre>';
    // exit ;

    $studentId    =   $_POST['studentId'];
    $name         =   $_POST['name'];
    $gender       =   $_POST['gender'];
    if (isset($_POST['gameId'])) 
    {
        $game        = $_POST['gameId'];
        $gameid      =   implode(',', $game);
    } 
    else 
    {
        $gameid = null;
    }
    if (!empty($_FILES['image']['name'])) 
    {
        $imagePath  = 'images/' . time() . $_FILES['image']['name'];
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) 
        {
            $image = $imagePath;
        } 
        else 
        {
            $image = null;
        }
    } 
    else 
    {
        $currentImageQuery = mysqli_query($connection, "SELECT image FROM student WHERE studentid = '$studentId'");
        $currentImage = mysqli_fetch_assoc($currentImageQuery);
        $image = $currentImage['image'];
    }
    $updateStudentQuery = "UPDATE student SET studentname = '$name', gender = '$gender', fkgameid = '$gameid', image = '$image' WHERE studentid = '$studentId'";
    $updateStudent = mysqli_query($connection, $updateStudentQuery);


    if ($updateStudent) 
    {
        $pksubjectids = implode(',', $_SESSION['pksubjectid']);
        $updateQuery = "UPDATE `studentsubject` SET `fkstudentid`='$studentId' WHERE studentsubjectid IN ($pksubjectids)";
        $updateSubject = mysqli_query($connection, $updateQuery);
        unset($_SESSION['pksubjectid']);
        unset($_SESSION['studentID']);
        $_SESSION['toastr'] = array(
            'type'    => 'success',
            'message' => 'Student Updated!',
            'title'   => 'Success!'
        );
        header('location: index.php');
    } 
    else 
    {
        $_SESSION['toastr'] = array(
            'type'    => 'error',
            'message' => 'Failed to update student data!',
            'title'   => 'Error!'
        );
    }
    // Update 'studentcity' 
    $delete_query = mysqli_query($connection, "DELETE FROM studentcity WHERE fkstudentid = '$studentId'");

    if ($delete_query === true) 
    {
        $cities = $_POST['city'];
        $provinces = $_POST['province'];
        for ($i = 0; $i < count($cities); $i++) {
            $city = mysqli_real_escape_string($connection, $cities[$i]);
            $province = mysqli_real_escape_string($connection, $provinces[$i]);

            // Insert new records
            $insert_query = mysqli_query($connection, "INSERT INTO studentcity (fkstudentid, city, province) VALUES ('$studentId', '$city', '$province')");
            if ($insert_query === false) 
            {
                $_SESSION['toastr'] = array(
                    'type' => 'error',
                    'message' => 'Error inserting studentcity data: ' . mysqli_error($connection),
                    'title' => 'Error!'
                );
            }
        }
    } else 
    {
        $_SESSION['toastr'] = array(
            'type' => 'error',
            'message' => 'Error deleting existing studentcity data: ' . mysqli_error($connection),
            'title' => 'Error!'
        );
    }
}
