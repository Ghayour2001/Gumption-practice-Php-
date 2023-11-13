
<?php
session_start();
include 'config/connection.php';
if (isset($_POST['subjectName']) && isset($_POST['marks'])) 
{
    $subjectName    =   $_POST['subjectName'];
    $marks          =   $_POST['marks'];
    $sql            =   "INSERT INTO studentsubject (subjectName, marks) VALUES ('$subjectName', '$marks')";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        $_SESSION['pksubjectid'][] = mysqli_insert_id($connection);
     
    $_SESSION['toastr'] = array(
        'type'    => 'success',
        'message' => 'Student Subject Saved!',
        'title'   => 'Success!'
    );
      
        echo "inserting data";
    } 
    else 
    {
        echo "Error inserting data into the database";
    }
}

 

?>
