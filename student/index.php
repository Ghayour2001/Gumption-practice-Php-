<?php
include 'config/connection.php';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" >



    <style>
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

        .loader-overlay:not(.submitting) .loader {
            animation: none;
        }

        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loader {
            width: 40px;
            height: 40px;
            position: relative;
        }

        .loader:before,
        .loader:after {
            content: "";
            position: absolute;
            border-radius: 50%;
        }

        .loader:before {
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 2px solid #ffffff;
            border-color: #ffffff transparent #ffffff transparent;
            animation: loader-before-spin 1.2s linear infinite;
        }

        .loader:after {
            top: 50%;
            left: 50%;
            width: 16px;
            height: 16px;
            transform: translate(-50%, -50%);
            background-color: #ffffff;
        }

        @keyframes loader-spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes loader-before-spin {

            0%,
            100% {
                transform: rotate(0deg);
            }

            50% {
                transform: rotate(180deg);
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <!-- loader Start -->
            <div id="loading" class="loader-overlay">
                <div class="loader">
                </div>
            </div>
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
                                    <td><?php echo $row['studentgender']; ?></td>
                                    <td><?php echo $row['subjects']; ?></td>
                                    <td><?php echo $row['countryname']; ?></td>
                                    <td><?php echo $row['provincename']; ?></td>
                                    <td><?php echo $row['cityname']; ?></td>
                                    <td><?php echo $row['studentaddress']; ?></td>
                                    <td><?php echo $row['skills']; ?></td>
                                    <td><img src="<?php echo $row['image']; ?>" alt="Student Image" width="100"></td>
                                    <td><a class="btn btn-info" href="create.php?action=edit&id=<?php echo $row['studentid']; ?>"><i class="fas fa-edit"></i></a></td>

                                    <td>
                                        <a class="btn btn-danger delete-btn" href="#" data-student-id="<?php echo $row['studentid']; ?>"><i class="fas fa-trash-alt"></i></a>
                                    </td>

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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            hideLoader();
            $('#student-table').DataTable();

            var responseMessage = localStorage.getItem('success_message');

            if (responseMessage) {

                toastr.success(responseMessage);
                // Clear the response message from localStorage if needed
                localStorage.removeItem('success_message');
            }

            // Add an event listener to the "Delete" button
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                var studentId = $(this).data('student-id');

                // Use SweetAlert to confirm the deletion
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var deleteUrl = 'delete.php?id=' + studentId;

                        // Send an Ajax request to delete.php
                        $.ajax({
                            type: 'GET',
                            url: deleteUrl,
                            beforeSend: function() {
                                showLoader();
                            },
                            success: function(response) {
                                var data = JSON.parse(response);
                                if (data.status === "success") {
                                    // Display success message in toastr
                                    toastr.success(data.message);
                                    // Reload the page after 2 seconds
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                    hideLoader();
                                } else if (data.status === "error") {
                                    // Display error message in toastr
                                    toastr.error(data.message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    }
                });
            });

        });
        // for loader spiner
        function showLoader() {
            $(".loader-overlay").fadeIn("slow");
        }

        function hideLoader() {
            $(".loader-overlay").fadeOut("slow");
        }
    </script>

</body>

</html>