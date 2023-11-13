<?php
include "config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    // Check if any validation errors occurred
    if (count($errors) == 0) {
        // Proceed with data insertion

        if (!empty($_FILES['image']['name'])) {
            $imagePath = 'images/' . time() . $_FILES['image']['name'];
            if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
                $image = $imagePath;
            } else {
                $image = null; // Handle the case where image upload fails
            }
        } else {
            $image = null;
        }

        $result = mysqli_query($connection, "INSERT INTO students(name, email, dob, address, gender, image, countryid, provinceid, cityid) 
                    VALUES ('$name', '$email', '$dob', '$address', '$gender', '$image', '$country', '$province', '$city')");

        if ($result) {
            $studentid = mysqli_insert_id($connection);

            // Insert student subjects
            if (isset($_POST['subject']) && is_array($_POST['subject'])) {
                foreach ($_POST['subject'] as $subjectid) {
                    $subjectid = mysqli_real_escape_string($connection, $subjectid);
                    $insertStudentSubject = "INSERT INTO studentsubject (studentid, subjectid) VALUES ('$studentid', '$subjectid')";
                    if (!mysqli_query($connection, $insertStudentSubject)) {
                        echo "Error inserting student_subject: " . mysqli_error($connection);
                    }
                }
            }

            // Insert student skills
            if (isset($_POST['skill']) && is_array($_POST['skill'])) {
                foreach ($_POST['skill'] as $skillid) {
                    $insertStudentSkill = "INSERT INTO studentskill (studentid, skillid) VALUES ('$studentid', '$skillid')";
                    if (!mysqli_query($connection, $insertStudentSkill)) {
                        echo "Error inserting student_skill: " . mysqli_error($connection);
                    }
                }
            }

            echo json_encode(array("status" => "success", "message" => "Data has been inserted successfully."));
        } else {
            echo json_encode(array("status" => "error", "errors" => $errors));
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
