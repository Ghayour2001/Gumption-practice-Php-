<?php
session_start();
include 'config/connection.php';

if (isset($_POST['save'])) {
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
        } else {
            $image = null;
        }
    } else {
        $image = null;
    }
    $result      =   mysqli_query($connection, "INSERT INTO student (studentname, gender,fkgameid,image) VALUES ('$name', '$gender','$gameid','$image')");
    if ($result !== TRUE) 
    {
        $_SESSION['toastr'] = array(
            'type'    => 'error', 
            'message' => 'Something wrong Insert student!',
            'title'   => 'error!'
        );
    }
    if ($result) {
        $studentid      =   mysqli_insert_id($connection);
        $subjectNames   =   $_POST['subjectName'];
        $Marks          =    $_POST['marks'];

        //  for subject and mark
        for ($i = 1; $i < count($subjectNames); $i++) 
        {
            $subjectName    =   $subjectNames[$i];
            $mark           =   $Marks[$i];
            $sql_subject = mysqli_query($connection, "INSERT INTO studentsubject (fkstudentid, subjectName, marks) VALUES ('$studentid', '$subjectName', '$mark')");

            if ($sql_subject !== TRUE) 
            {
                $_SESSION['toastr'] = array(
                    'type'    => 'error', 
                    'message' => 'Error inserting subject data: ' . mysqli_error($connection),
                    'title'   => 'Error!'
                );
            }
            
        }

        // Insert 'studentcity' 
        $cities        =   $_POST['city'];
        $provinces     =   $_POST['province'];
        for ($i = 0; $i < count($cities); $i++) {
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
    }
   

// if ($result && $sql_subject && $sql_city) 
// {
//     echo 'done';
//     echo '<script>
//       function() {
//         toastrMessege("success", "This is a success message");
//       };
//         </script>';
//   }

  if (($result && $sql_subject && $sql_city)) 
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

?>