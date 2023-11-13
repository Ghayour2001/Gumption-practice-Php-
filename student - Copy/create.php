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
//         echo '<script> setTimeout(function() {
//             window.location.href = "index.php";}, 2000); </script>';
//     } else {
//         $_SESSION['message'] = 'Something went wrong while inserting data';
//         $_SESSION['message_type'] = 'error';
//     }
// }





//--------------------EDIT------------------------------------------------------------

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $stdID = $_GET['id'];

    $query = mysqli_query($connection, "SELECT students.id AS studentid, students.name AS studentname,
     students.email AS studentemail, students.dob AS studentdob, students.gender AS studentgender, students.address AS studentaddress,
      students.image, city.cname AS cityname,city.id AS city_id, province.pname AS provincename, province.id As province_id, 
      countries.name AS countryname, GROUP_CONCAT(DISTINCT subject.subjectname ORDER BY subject.subjectname ASC) AS subjects, 
      GROUP_CONCAT(DISTINCT skill.skillname ORDER BY skill.skillname ASC) AS skills 
      FROM students LEFT JOIN city ON students.cityid = city.id LEFT JOIN province ON students.provinceid = province.id
        LEFT JOIN countries ON students.countryid = countries.id
        LEFT JOIN studentsubject ON students.id = studentsubject.studentid 
         LEFT JOIN subject ON studentsubject.subjectid = subject.subjectid 
        LEFT JOIN studentskill ON students.id = studentskill.studentid 
     LEFT JOIN skill ON studentskill.skillid = skill.skillid WHERE students.id = '$stdID';");
    $row1 = mysqli_fetch_array($query);
}
//------------UPDATE-----------------------------

//--------------------DELETE------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id'])) {
    $studentID = $_GET['id'];

    // Delete student's subjects
    mysqli_query($connection, "DELETE FROM studentsubject WHERE studentid = '$studentID'");

    // Delete student's skills
    mysqli_query($connection, "DELETE FROM studentskill WHERE studentid = '$studentID'");

    // Delete student's record
    $deleteQuery = "DELETE FROM students WHERE id = '$studentID'";
    if (mysqli_query($connection, $deleteQuery)) {
        $_SESSION['message'] = 'Data has been deleted successfully';
        $_SESSION['message_type'] = 'success';
        // // Redirect back to the student list or any other appropriate page
        echo '<script> setTimeout(function() {
            window.location.href = "index.php";}, 2000); </script>';
    } else {
        $_SESSION['message'] = 'Something went wrong while deleting data';
        $_SESSION['message_type'] = 'error';
    }
}

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
        .image-div img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .success {
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
        }

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

        .form-heading {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        select.form-control {
            height: auto;
        }

        .radio-group label {
            margin-right: 15px;
        }

        .btn-submit {
            background-color: #428bca;
            border: none;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #357ebd;
        }

        .error {
            color: red;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($stdID)) { ?>
                    <h2 class="text-center text-success mt-5">Update Student Recod</h2>
                <?php } else { ?>
                    <h2 class="text-center text-success mt-5">Student Recod</h2>
                <?php } ?>

                <div class="message-container">
                    <?php
                    if (isset($_SESSION['message']) && isset($_SESSION['message_type'])) {
                        $message = $_SESSION['message'];
                        $messageType = $_SESSION['message_type'];

                        // Clear the session variables after displaying the message
                        unset($_SESSION['message']);
                        unset($_SESSION['message_type']);
                    }
                    ?>
                </div>

                <form id="student-form" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editName">Student Name<span class="text-danger">*</span></label>
                                <input id="editName" class="form-control" type="text" name="name" value="<?php echo isset($row1['studentname']) ? $row1['studentname'] : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Email<span class="text-danger">*</span></label>
                                <input id="email" class="form-control" type="email" name="email" value="<?php echo isset($row1['studentemail']) ? $row1['studentemail'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth<span class="text-danger">*</span></label>
                                <input id="dob" class="form-control" type="date" name="dob" value="<?php echo isset($row1['studentdob']) ? $row1['studentdob'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="country">Country<span class="text-danger">*</span></label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">--Select Country--</option>
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM countries');
                                    $countries = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($countries as $country) {
                                        $id = $country['id'];
                                        $countryName = $country['name'];
                                        $selected = (isset($row1['countryname']) && $row1['countryname'] == $countryName) ? 'selected' : '';
                                        echo '<option value="' . $id . '" ' . $selected . '>' . $countryName . '</option>';
                                    }
                                    if (empty($countries)) {
                                        echo '<option value="0">Country not found</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="province">Province<span class="text-danger">*</span></label>
                                <select name="province" id="province" class="form-control">
                                    <?php if (isset($stdID)) { ?>

                                        <option value="<?php echo isset($row1['province_id']) ? $row1['province_id'] : ''; ?>"><?php echo isset($row1['provincename']) ? $row1['provincename'] : ''; ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="city">City<span class="text-danger">*</span></label>
                                <select name="city" id="city" class="form-control">
                                    <?php if (isset($stdID)) { ?>

                                        <option value="<?php echo isset($row1['city_id']) ? $row1['city_id'] : ''; ?>"><?php echo isset($row1['cityname']) ? $row1['cityname'] : ''; ?></option>
                                    <?php } ?>

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject<span class="text-danger">*</span></label>
                                <select name="subject[]" id="subject" class="form-control" multiple="multiple">
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM subject');
                                    $subjects = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($subjects as $subject) {
                                        $id = $subject['subjectid'];
                                        $subjectName = $subject['subjectname'];
                                        $selected = (isset($row1['subjects']) && in_array($subjectName, explode(",", $row1['subjects']))) ? 'selected' : '';
                                        echo '<option value="' . $id . '" ' . $selected . '>' . $subjectName . '</option>';
                                    }
                                    if (empty($subjects)) {
                                        echo '<option value="0">Subjects not found</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <div class="image-div">
                                    <img src="<?php echo isset($row1['image']) ? $row1['image'] : 'images/avatar.png'; ?>" alt="" id="selected-img">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Image<span class="text-danger">*</span></label>
                                <input type="file" name="image" class="form-control" id="image">
                                <input type="hidden" name="oldimage" class="form-control" value="<?php echo isset($row1['image']) ? $row1['image'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="address">Address<span class="text-danger">*</span></label>
                                <textarea name="address" id="address" class="form-control" cols="30" rows="3"><?php echo isset($row1['studentaddress']) ? $row1['studentaddress'] : ''; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender<span class="text-danger">*</span></label>
                                <input type="radio" name="gender" id="male" value="male" <?php echo (!isset($row1['studentgender']) || $row1['studentgender'] === 'Male') ? 'checked' : ''; ?>>Male
                                <input type="radio" name="gender" id="female" value="female" <?php echo (isset($row1['studentgender']) && $row1['studentgender'] === 'Female') ? 'checked' : ''; ?>>Female
                            </div>

                            <div class="form-group">
                                <label for="skill">Skills</label>
                                <select name="skill[]" id="skill" class="form-control" multiple="multiple">
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM Skill');
                                    $Skills = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($Skills as $Skill) {
                                        $id = $Skill['skillid'];
                                        $skillName = $Skill['skillname'];
                                        $selected = (isset($row1['skills']) && in_array($skillName, explode(",", $row1['skills']))) ? 'selected' : '';
                                        echo '<option value="' . $id . '" ' . $selected . '>' . $skillName . '</option>';
                                    }
                                    if (empty($Skills)) {
                                        echo '<option value="0">Skills not found</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <?php if (isset($stdID)) { ?>
                                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                                <?php } else { ?>
                                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>

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
        // Display success or error message using toastr library

        // if ($messageType === 'success' || $messageType === 'error') {
        //     echo 'if (typeof toastr !== "undefined") {';
        //     if ($messageType === 'success') {
        //         echo 'toastr.success("' . $message . '");';
        //     } else if ($messageType === 'error') {
        //         echo 'toastr.error("' . $message . '");';
        //     }
        //     echo '}';
        // }
    </script>

    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('#subject').select2({
                multiple: true, // Enable multi-select
                placeholder: 'Select subjects'
            });
            $('#skill').select2({
                multiple: true, // Enable multi-select
                placeholder: 'Select skill'
            });
            // Get the message container element
            var messageContainer = $('.message-container');
            // Hide the message container after 2 seconds (2000 milliseconds)
            setTimeout(function() {
                messageContainer.hide();
            }, 2000); // Adjust this delay as needed
            //    when i select the image from file it display in the above image div 
            $('#image').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#selected-img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

        });

        // getting provinces related to country 
        $(document).ready(function() {
            $('#country').change(function() {
                var countryId = $(this).val();
                $('#province').empty();
                $('#city').empty(); // Clear previous options

                if (countryId !== '') {
                    $.ajax({
                        url: 'get_provinces.php',
                        type: 'GET',
                        data: {
                            countryId: countryId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $('#province').append($('<option>', {
                                    value: '',
                                    text: 'Select  provinces'
                                }));

                                $.each(data, function(index, province) {
                                    $('#province').append($('<option>', {
                                        value: province.id,
                                        text: province.pname
                                    }));
                                });
                            } else {
                                $('#province').append($('<option>', {
                                    value: '',
                                    text: 'No provinces available'
                                }));
                            }
                        },
                        error: function() {
                            console.log('Error fetching province data.');
                        }
                    });
                } else {
                    $('#province').empty(); // Clear options when no country is selected
                }
            });


            $('#province').change(function() {
                var provinceId = $(this).val();
                $('#city').empty();

                if (provinceId !== '') {
                    $.ajax({
                        url: 'get_cities.php', // Replace with your server-side script to fetch city data
                        type: 'GET',
                        data: {
                            provinceId: provinceId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.length > 0) {
                                $.each(data, function(index, city) {
                                    $('#city').append($('<option>', {
                                        value: city.id,
                                        text: city.cname
                                    }));
                                });
                            } else {
                                $('#city').append($('<option>', {
                                    value: '',
                                    text: 'No cities available'
                                }));
                            }
                        },
                        error: function() {
                            console.log('Error fetching city data.');
                        }
                    });
                }
            });

            $('#student-form').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                // Gather form data
                var formData = new FormData($('#student-form')[0]);
                var url = 'insert.php';
                // Check if student ID is available
                <?php if (isset($stdID) && !empty($stdID)) { ?>
                    url = 'update.php?id=<?php echo $stdID ?>';
                <?php } ?>
                // Send the data using Ajax
                $.ajax({
                    type: 'POST',
                    url: url, // Replace with your server-side processing script
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.status === "success") {
                            // Reset the form upon successful response
                            $('#student-form')[0].reset();
                            // $('.image-preview').html('<img src="image/avatar.jpg" alt="Avatar">');

                            // Store the success message in localStorage
                            localStorage.setItem('success_message', data.message);

                            // Redirect to studentlist.php
                            window.location.href = "index.php";
                        } else if (data.status === "error") {
                            var errorMessage = data.message;

                            // Display the error message using toastr, mentioning the specific field
                            toastr.error(errorMessage);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors, if any
                        console.error(error); // Log the error for debugging purposes
                    }
                });
            });
        });



        // jQuery('#student-form').validate({
        //     rules: {
        //         name: 'required',
        //         email: {
        //             required: true,
        //             email: true,
        //         },
        //         dob: {
        //             required: true,
        //         },
        //         country: {
        //             required: true,
        //         },
        //         province: {
        //             required: true,
        //         },
        //         city: {
        //             required: true,
        //         },
        //         subject: {
        //             required: true,
        //         },
        //         address: {
        //             required: true,
        //         },
        //         gender: {
        //             required: true,
        //         },
        //         skill: {
        //             required: true,
        //         },
        //     },
        //     messages: {
        //         name: 'Please enter the student name.',
        //         email: {
        //             required: 'Please enter an email address.',
        //             email: 'Please enter a valid email address.',
        //         },
        //         dob: 'Please select a date of birth.',
        //         country: 'Please select a country.',
        //         province: 'Please select a province.',
        //         city: 'Please select a city.',
        //         subject: 'Please select at least one subject.',
        //         address: 'Please enter an address.',
        //         gender: 'Please select a gender.',
        //         skill: 'Please select at least one skill.',
        //     },
        //     submitHandler: function(form) {
        //         form.submit();
        //     },
        // });
    </script>


</body>

</html>