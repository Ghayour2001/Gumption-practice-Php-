<?php
include "config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentID = isset($_GET['id']) ? $_GET['id'] : null;

    $errors = array(); // Array to store validation errors

    // Server-side validation
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    if (empty($name)) {
        $errors['name'] = "Student name is required.";
    }

    $email = mysqli_real_escape_string($connection, $_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    $dob = mysqli_real_escape_string($connection, $_POST['dob']);
    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required.";
    }

    $gender = (isset($_POST['gender'])) ? mysqli_real_escape_string($connection, $_POST['gender']) : '';
    if (empty($gender)) {
        $errors['gender'] = "Gender is required.";
    }

    $address = mysqli_real_escape_string($connection, $_POST['address']);
    if (empty($address)) {
        $errors['address'] = "Address is required.";
    }

    $country = isset($_POST['country']) ? mysqli_real_escape_string($connection, $_POST['country']) : '';
    if (empty($country)) {
        $errors['country'] = "Country is required.";
    }

    $province = isset($_POST['province']) ? mysqli_real_escape_string($connection, $_POST['province']) : '';
    if (empty($province)) {
        $errors['province'] = "Province is required.";
    }

    $city = isset($_POST['city']) ? mysqli_real_escape_string($connection, $_POST['city']) : '';

    // ... Perform similar validation for other fields ...

    // Check if any validation errors occurred
    if (count($errors) == 0) {
        // Proceed with data insertion or update

        $image = ''; // Initialize image variable

        if (!empty($_FILES['image']['name'])) {
            $imagePath = 'images/' . time() . $_FILES['image']['name'];
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                // Delete old image if needed
                // if ($studentID !== null) {
                    $oldImageQuery = mysqli_query($connection, "SELECT image FROM students WHERE id = '$studentID'");
                    $oldImageRow = mysqli_fetch_assoc($oldImageQuery);
                    if ($oldImageRow['image'] !== null && file_exists($oldImageRow['image'])) {
                        unlink($oldImageRow['image']);
                    }
                // }
                $image = $imagePath;
            } else {
                // Handle the case where image upload fails
                $image = isset($_POST['oldimage']) ? $_POST['oldimage'] : '';
            }
        } else {
            $image = isset($_POST['oldimage']) ? $_POST['oldimage'] : '';
        }

   
  
        $updateQuery = "UPDATE students SET
                                            name = '$name', email = '$email', dob = '$dob', address = '$address', gender = '$gender', image = '$image',
                                            countryid = '$country', provinceid = '$province', cityid = '$city'
                                            WHERE id = '$studentID'";

        if (mysqli_query($connection, $updateQuery)) {
            // Update student's subjects
            if (isset($_POST['subject']) && is_array($_POST['subject'])) {
                mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");
                foreach ($_POST['subject'] as $subjectid) {
                    $subjectid = mysqli_real_escape_string($connection, $subjectid);
                    $insertStudentSubject = "INSERT INTO studentsubject (studentid, subjectid) VALUES ('$studentID', '$subjectid')";
                    if (!mysqli_query($connection, $insertStudentSubject)) {
                        echo "Error updating student_subject: " . mysqli_error($connection);
                    }
                }
            }

            // Update student's skills
            if (isset($_POST['skill']) && is_array($_POST['skill'])) {
                mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");
                foreach ($_POST['skill'] as $skillid) {
                    $insertStudentSkill = "INSERT INTO studentskill (studentid, skillid) VALUES ('$studentID', '$skillid')";
                    if (!mysqli_query($connection, $insertStudentSkill)) {
                        echo "Error updating student_skill: " . mysqli_error($connection);
                    }
                }
            }

            echo json_encode(array("status" => "success", "message" => "Data has been updated successfully."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Something went wrong while updating data."));
        }
    } else {
        // Find the first field with an error
        $firstErrorField = array_key_first($errors);
        echo json_encode(array("status" => "error", "field" => $firstErrorField, "message" => $errors[$firstErrorField]));
    }
} else {
    echo 'Invalid request';
}
?>
