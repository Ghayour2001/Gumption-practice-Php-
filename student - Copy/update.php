<?php
include "config/connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = $_GET['id']; // Assuming you're getting the student ID from the URL parameter

    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];
    $country = $_POST['country'];
    $province = $_POST['province'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';

        if (!empty($_FILES['image']['name'])) {
            $imagePath = 'images/' . time() . $_FILES['image']['name'];
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // Delete old image if needed
                if (!empty($_POST['oldimage']) && file_exists($_POST['oldimage'])) {
                    unlink($_POST['oldimage']);
                }
                $image = $imagePath;
            } else {
                // Handle the case where image upload fails
                $image = isset($_POST['oldimage']) ? $_POST['oldimage'] : '';
            }
        } else {
            $image = isset($_POST['oldimage']) ? $_POST['oldimage'] : '';
        }

    $updateQuery = "UPDATE students 
                    SET name = '$name', email = '$email', dob = '$dob', address = '$address', gender = '$gender', image = '$image',
                        countryid = '$country', provinceid = '$province', cityid = '$city'
                    WHERE id = '$studentID'";

    if (mysqli_query($connection, $updateQuery)) {
        // Update student's subjects
        if (isset($_POST['subject']) && is_array($_POST['subject'])) {
            $selectedSubjects = $_POST['subject'];
            mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");
            foreach ($selectedSubjects as $subjectID) {
                $subjectID = mysqli_real_escape_string($connection, $subjectID);
                mysqli_query($connection, "INSERT INTO studentsubject (studentid, subjectid) VALUES ('$studentID', '$subjectID')");
            }
        }

        // Update student's skills
        if (isset($_POST['skill']) && is_array($_POST['skill'])) {
            $selectedSkills = $_POST['skill'];
            mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");
            foreach ($selectedSkills as $skillID) {
                mysqli_query($connection, "INSERT INTO studentskill (studentid, skillid) VALUES ('$studentID', '$skillID')");
            }
        }

        echo 'Data has been updated successfully';


    } else {
        echo 'Something went wrong while updating data';
    
    }
}
?>