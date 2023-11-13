<?php
include 'config/connection.php';
// if (isset($_POST['save'])) {
//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $dob = $_POST['dob'];
//     $country = $_POST['country'];
//     $province = $_POST['province'];
//     $city = (isset($_POST['city'])) ? $_POST['city'] : '';
//     $address = $_POST['address'];
//     $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';
//     if (!empty($_FILES['image']['name'])) {
//         $imagePath = 'images/' . time() . $_FILES['image']['name'];
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
//             $image = $imagePath;
//         } else {
//             $image = null; // Handle the case where image upload fails
//         }
//     } else {
//         $image = null;
//     }

//     $result = mysqli_query($connection, "INSERT INTO students(name, email, dob, address, gender, image,countryid,provinceid,cityid)VALUES ('$name', '$email', '$dob', '$address', '$gender','$image','$country','$province','$city')");

//     if ($result) {
//         $studentid = mysqli_insert_id($connection);
//         // insert student subject 
//         if (isset($_POST['subject']) && is_array($_POST['subject']) && count($_POST['subject']) > 0) {
//             $selectedSubjects = $_POST['subject'];
//             foreach ($selectedSubjects as $subjectid) {
//                 $subjectid = mysqli_real_escape_string($connection, $subjectid);
//                 $insertStudentSubject = "INSERT INTO studentsubject (studentid, subjectid) VALUES ('$studentid', '$subjectid')";
//                 if (!mysqli_query($connection, $insertStudentSubject)) {
//                     echo "Error inserting student_subject: " . mysqli_error($connection);
//                 }
//             }
//         }
//         // insert students skill 
//         if (isset($_POST['skill']) && is_array($_POST['skill'])) {
//             $selectedskills = $_POST['skill'];
//             foreach ($selectedskills as $skillid) {
//                 $insertStudentskill = "INSERT INTO studentskill (studentid, skillid) VALUES ('$studentid', '$skillid')";
//                 if (!mysqli_query($connection, $insertStudentskill)) {
//                     echo "Error inserting student_skill: " . mysqli_error($connection);
//                 }
//             }
//         }

//         $_SESSION['message'] = 'Data has been inserted successfully';
//         $_SESSION['message_type'] = 'success';
//     } else {
//         $_SESSION['message'] = 'Something went wrong while inserting data';
//         $_SESSION['message_type'] = 'error';
//     }
// }





//--------------------EDIT------------------------------------------------------------

// if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
//     $stdID = $_GET['id'];

//     $query = mysqli_query($connection, "SELECT students.id AS studentid, students.name AS studentname,
//      students.email AS studentemail, students.dob AS studentdob, students.gender AS studentgender, students.address AS studentaddress,
//       students.image, city.cname AS cityname,city.id AS city_id, province.pname AS provincename, province.id As province_id, 
//       countries.name AS countryname, GROUP_CONCAT(DISTINCT subject.subjectname ORDER BY subject.subjectname ASC) AS subjects, 
//       GROUP_CONCAT(DISTINCT skill.skillname ORDER BY skill.skillname ASC) AS skills 
//       FROM students LEFT JOIN city ON students.cityid = city.id LEFT JOIN province ON students.provinceid = province.id
//         LEFT JOIN countries ON students.countryid = countries.id
//         LEFT JOIN studentsubject ON students.id = studentsubject.studentid 
//          LEFT JOIN subject ON studentsubject.subjectid = subject.subjectid 
//         LEFT JOIN studentskill ON students.id = studentskill.studentid 
//      LEFT JOIN skill ON studentskill.skillid = skill.skillid WHERE students.id = '$stdID';");
//     $row1 = mysqli_fetch_array($query);
// }
// //------------UPDATE-----------------------------
// if (isset($_POST['update'])) {
//     $studentID = $_GET['id']; // Assuming you're getting the student ID from the URL parameter

//     $name = $_POST['name'];
//     $email = $_POST['email'];
//     $dob = $_POST['dob'];
//     $country = $_POST['country'];
//     $province = $_POST['province'];
//     $city = $_POST['city'];
//     $address = $_POST['address'];
//     $gender = (isset($_POST['gender'])) ? $_POST['gender'] : '';

//     // Update image if a new one is provided
//     $image = $row1['image'];
//     if (!empty($_FILES['image']['name'])) {
//         $imagePath = 'images/' . time() . $_FILES['image']['name'];
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
//             $image = $imagePath;
//         }
//     }

//     $updateQuery = "UPDATE students 
//                     SET name = '$name', email = '$email', dob = '$dob', address = '$address', gender = '$gender', image = '$image',
//                         countryid = '$country', provinceid = '$province', cityid = '$city'
//                     WHERE id = '$studentID'";

//     if (mysqli_query($connection, $updateQuery)) {
//         // Update student's subjects
//         if (isset($_POST['subject']) && is_array($_POST['subject'])) {
//             $selectedSubjects = $_POST['subject'];
//             mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");
//             foreach ($selectedSubjects as $subjectID) {
//                 $subjectID = mysqli_real_escape_string($connection, $subjectID);
//                 mysqli_query($connection, "INSERT INTO studentsubject (studentid, subjectid) VALUES ('$studentID', '$subjectID')");
//             }
//         }

//         // Update student's skills
//         if (isset($_POST['skill']) && is_array($_POST['skill'])) {
//             $selectedSkills = $_POST['skill'];
//             mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");
//             foreach ($selectedSkills as $skillID) {
//                 mysqli_query($connection, "INSERT INTO studentskill (studentid, skillid) VALUES ('$studentID', '$skillID')");
//             }
//         }

//         $_SESSION['message'] = 'Data has been updated successfully';
//         $_SESSION['message_type'] = 'success';
//         echo '<script> setTimeout(function() {
//         window.location.href = "index.php";}, 2000); </script>';
//     } else {
//         $_SESSION['message'] = 'Something went wrong while updating data';
//         $_SESSION['message_type'] = 'error';
//     }
// }

//--------------------DELETE------------------------------------------------------------
// if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
//     $studentID = $_GET['id'];

//     // Delete student's subjects
//     mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");

//     // Delete student's skills
//     mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");

//     // Delete student's record
//     $deleteQuery = "DELETE FROM students WHERE id = '$studentID'";
//     if (mysqli_query($connection, $deleteQuery)) {
//         $_SESSION['message'] = 'Data has been deleted successfully';
//         $_SESSION['message_type'] = 'success';
//         // // Redirect back to the student list or any other appropriate page
//         echo '<script> setTimeout(function() {
//             window.location.href = "index.php";}, 2000); </script>';
//     } else {
//         $_SESSION['message'] = 'Something went wrong while deleting data';
//         $_SESSION['message_type'] = 'error';
//     }
// }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Record</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <!-- Include Select2 CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">


    <style>
        /* .success {
            background-color: #dff0d8;
            color: #3c763d;
            border: 1px solid #d6e9c6;
            padding: 10px;
            margin-bottom: 10px;
        }

        .danger {
            background-color: #f2dede;
            color: #a94442;
            border: 1px solid #ebccd1;
            padding: 10px;
            margin-bottom: 10px;
        } */

        body {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }
    </style>
</head>

<body>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center text-success mt-5">Student Recod List</h2>
                <a href="create.php" class="btn btn-info float-right">Add New</a>
                <div class="table-responsive pt-1">
                    <table id="student-table" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Email</th>
                                <th>Date of Birth</th>
                                <th>Gender</th>
                                <th>Subject</th>
                                <th>Country</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Skills</th>
                                <th>Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($connection, 'SELECT
                            students.id AS studentid,
                            students.name AS studentname,
                            students.email AS studentemail,
                            students.dob AS studentdob,
                            students.gender AS studentgender,
                            students.address AS studentaddress,
                            students.image,
                            city.cname AS cityname,
                            province.pname AS provincename,
                            countries.name AS countryname,
                            GROUP_CONCAT(DISTINCT subject.subjectname ORDER BY subject.subjectname ASC) AS subjects,
                            GROUP_CONCAT(DISTINCT skill.skillname ORDER BY skill.skillname ASC) AS skills
                        FROM students
                        LEFT JOIN city ON students.cityid = city.id
                        LEFT JOIN province ON students.provinceid = province.id
                        LEFT JOIN countries ON students.countryid = countries.id
                        LEFT JOIN studentsubject ON students.id = studentsubject.studentid
                        LEFT JOIN subject ON studentsubject.subjectid = subject.subjectid
                        LEFT JOIN studentskill ON students.id = studentskill.studentid
                        LEFT JOIN skill ON studentskill.skillid = skill.skillid
                        GROUP BY
                            students.id, students.name, students.email, students.dob, students.gender,
                            students.address, students.image, city.cname, province.pname, countries.name;');

                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['studentname']; ?></td>
                                    <td><?php echo $row['studentemail']; ?></td>
                                    <td><?php echo $row['studentdob']; ?></td>
                                    <td><?php echo $row['studentgender'];?></td>
                                    <td><?php echo $row['subjects']; ?></td>
                                    <td><?php echo $row['countryname']; ?></td>
                                    <td><?php echo $row['provincename']; ?></td>
                                    <td><?php echo $row['cityname']; ?></td>
                                    <td><?php echo $row['studentaddress']; ?></td>
                                    <td><?php echo $row['skills']; ?></td>
                                    <td><img src="<?php echo $row['image']; ?>" alt="Student Image" width="100"></td>
                                    <td><a class="btn btn-info" href="create.php?action=edit&id=<?php echo $row['studentid']; ?>">Edit</a></td>

                                    <td><a class="btn btn-info" href="create.php?action=delete&id=<?php echo $row['studentid']; ?>">Delete</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>




    <script>
        $(document).ready(function() {
            $('#student-table').DataTable();
    
            var responseMessage = localStorage.getItem('success_message');

            if (responseMessage) {
              
                toastr.success(responseMessage);
                // Clear the response message from localStorage if needed
                localStorage.removeItem('success_message');
            }
        });
    </script>


</body>

</html>