
<?php
session_start();
include 'config/connection.php';
if (isset($_POST['pksubjectid']) && isset($_POST['marks'])) 

{
    $pksubjectid    =   $_POST['pksubjectid'];
    $marks          =   $_POST['marks'];
    $totalmarks     =   $_POST['totalmarks'];
    $sql            =   "INSERT INTO studentsubject (fksubjectid, marks,totalmarks) VALUES ('$pksubjectid', '$marks','$totalmarks')";
    $result = mysqli_query($connection, $sql);

    if ($result) 
    {
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
