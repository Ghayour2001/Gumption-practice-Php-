<?php
session_start();
include 'config/connection.php';
if (isset($_REQUEST['id'])) {
    $studentID      =   $_REQUEST['id'];
    // echo $studentID ;


    if (isset($studentID)) {
        if (!isset($_SESSION['studentID']) || !is_array($_SESSION['studentID'])) {
            $_SESSION['studentID'] = [];
        }
        $_SESSION['studentID'][] = $studentID;
        // print_r($_SESSION['studentID']);
    }

    if (!empty($_SESSION['pksubjectid'])) {

        // print_r($_SESSION['pksubjectid']);
    }


    $query = "SELECT
    s.studentid,
    s.studentname,
    s.gender,
    GROUP_CONCAT(DISTINCT sub.subjectName) AS subjectNames,
    (
        SELECT GROUP_CONCAT(DISTINCT id)
        FROM games
        WHERE FIND_IN_SET(games.id, s.fkgameid)
    ) AS game_name,
    GROUP_CONCAT(DISTINCT ss.marks) AS marks,
    GROUP_CONCAT(DISTINCT sc.city) AS cities,
    GROUP_CONCAT(DISTINCT sc.province) AS provinces,
    s.image
FROM student s
LEFT JOIN studentsubject ss ON s.studentid = ss.fkstudentid
LEFT JOIN studentcity sc ON s.studentid = sc.fkstudentid
LEFT JOIN subject sub ON ss.fksubjectid = sub.subjectid
WHERE s.studentid = '$studentID'
GROUP BY s.studentid, s.studentname, s.gender, s.image, s.fkgameid;
;
    ";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);
    // echo '<pre>';
    // print_r($row);
    // echo '</pre>';
    // print_r($row);
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
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .image-div img {
            width: 200;
            height: 150px;
            border-radius: 10%;
        }

        .main1 {
            padding: 40px;
        }

        .main2 {
            padding: 40px;
        }

        .horizontal-game-list {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }

        .game-label {
            margin-right: 20px;
        }
    </style>


</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-10 offset-1">
                <?php if (isset($studentID)) { ?>
                    <h2 class="text-center text-success mt-5">Update Student Record</h2>
                <?php } else { ?>
                    <h2 class="text-center text-success mt-5">Student Record</h2>
                <?php } ?>

                <form id="student-form" method="post" action="insert.php" enctype="multipart/form-data">
                    <div class="main1  border border-secondary">
                        <h3 class="text-success text-center ">Personal Info</h3>
                        <div class="form-group row">
                            <input id="studentId" class="form-control" type="hidden" name="studentId" value="<?php echo isset($row['studentid']) ? $row['studentid'] : ''; ?>">
                            <label for="Name" class="col-sm-2 col-form-label">Student Name<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input id="Name" class="form-control" type="text" name="name" value="<?php echo isset($row['studentname']) ? $row['studentname'] : ''; ?>">
                                <span class="error" id="name-error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gender" class="col-sm-2 col-form-label">Gender<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="radio" name="gender" id="male" value="male" <?php echo (!isset($row['gender']) || ($row['gender']) == 'male') ? 'checked' : ''; ?>>Male
                                <input type="radio" name="gender" id="female" value="female" <?php echo (isset($row['gender']) && ($row['gender']) == 'female') ? 'checked' : ''; ?>>Female
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="games" class="col-sm-2 col-form-label">Games<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <div class="horizontal-game-list">
                                    <?php
                                    $sql = "SELECT * FROM `games`";
                                    $result = mysqli_query($connection, $sql);
                                    if (!empty($result)) {
                                        while ($row1 = mysqli_fetch_array($result)) 
                                        {
                                            $gameId = $row1['id'];
                                            $gameName = $row1['name'];
                                            $isChecked = ''; // Initialize as an empty string
                                            if (!empty($row['game_name'])) {
                                                $gameArray = explode(',', $row['game_name']);
                                                if (in_array($gameId, $gameArray)) {
                                                    $isChecked = 'checked';
                                                }
                                            }
                            echo '<label class="game-label">
                            <input class="checkBoxes" type="checkbox" name="gameId[]" value="' . $gameId . '" ' . $isChecked . '> ' . $gameName . '
                                   </label>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="image" class="col-sm-2 col-form-label">Image<span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="file" name="image" class="form-control" id="image">
                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <label class="col-sm-2 col-form-label">Preview</label>
                            <div class="col-sm-8">
                                <div class="image-div">
                                    <?php if (isset($row['image'])) { ?>
                                        <img src="<?php echo $row['image']; ?>" alt="No img Found" id="selected-img">
                                    <?php } else { ?>
                                        <img src="images/avatar.png" alt="No img Found" id="selected-img">
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="main2 col-md-12 mt-3 mb-5 border border-info">
                        <h3 class="text-success text-center ">Subject Chosen & Marks Obtained</h3>
                        <div class="form-group row">
                            <input type="hidden" id="subjectId" name="subjectId"> <!-- Hidden field to store subject ID -->
                            <!-- <label for="firstName" class="col-form-label">Subject Name</label> -->
                            <div class="col-sm-3">
                            <select name="pksubjectid[]" id="pksubjectid" class="form-control">
                                <option value="">Select Subject</option>
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM subject');
                                    $subjects = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    // $subjects = mysqli_fetch_assoc($select);
                                    foreach ($subjects as $subject) {
                                        $pksubjectid = $subject['subjectid'];
                                        $subjectName = $subject['subjectName'];

                                          $selected = ($pksubjectid == $pksubjectid_to_select) ? 'selected' : '';
        
        echo '<option value="' .         $pksubjectid . '" ' . $selected . '>' . $subjectName . '</option>';
                                    }
                                    if (empty($subjects)) {
                                        echo '<option value="0">Subjects not found</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- <label for="lastName" class="col-form-label">Obtain Marks</label> -->
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="marks[]" id="marks" placeholder="Enter Obtain Marks">
                            </div>
                            <!-- <label for="lastName" class="col-form-label">Total Marks</label> -->
                            <div class="col-sm-3">
                                <input type="text" class="form-control" name="totalmarks[]" id="totalmarks" placeholder="Enter Total Marks">
                            </div>
                            <div class="col-sm-3">
                                <button type="button" id="addButton" onclick="insertsubject()" class="btn btn-primary">Add Subject</button>
                            </div>
                        </div>



                        <table id="dataTable" class="table">
                            <thead>
                                <tr>

                                    <th>S.NO</th>
                                    <th>Subject Name</th>
                                    <th>Marks</th>
                                    <th>Total Marks</th>
                                    <th>%Age</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="subjecttbl">
                                <!-- Table rows will be added here -->


                            </tbody>
                        </table>
                        <!-- .....................................  -->
                        <div class="form-group row">
                            <div class="table-responsive">
                                <h3 class="text-success text-center ">Cities You Like</h3>
                                <table class="table table-bordered" id="dynamic_field">
                                    <tr>
                                        <th>Serial No</th>
                                        <th>City</th>
                                        <th>Province</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php
                                    if (isset($studentID)) {
                                        $sql = "SELECT `studentcityid`, `city`, `province` FROM `studentcity` WHERE `fkstudentid` = '$studentID'";
                                        $result = mysqli_query($connection, $sql);

                                       if (!empty($result)) { ?>
                                            <?php $rowNum = 1; ?>
                                            <?php while ($row1 = mysqli_fetch_array($result)) { ?>
                                                <tr>
                                                    <td><?php echo $rowNum; ?></td>
                                                    <td><input type="text" name="city[]" placeholder="Enter your city" class="form-control name_list" value="<?php echo $row1['city']; ?>"></td>
                                                    <td><input type="text" name="province[]" placeholder="Enter province" class="form-control name_list" value="<?php echo $row1['province']; ?>"></td>
                                                    <td><button type="button" name="remove" class="btn btn-danger btn_remove" data-city-id="<?php echo $row1['studentcityid']; ?>">X</button></td>
                                                </tr>
                                                <?php $rowNum++; ?>
                                            <?php } ?>
                                        <?php } ?>
                                        
                             
                                        <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                    <?php
                                    }
                                     else 
                                    {
                                    ?>
                                        <tr>
                                            <td>1</td>
                                            <td><input type="text" name="city[]" placeholder="Enter your city" class="form-control name_list" /></td>
                                            <td><input type="text" name="province[]" placeholder="Enter Province" class="form-control name_list" /></td>
                                            <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </table>

                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-8 text-center">
                                <?php
                                if (isset($studentID)) {
                                ?>
                                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                                <?php
                                } else {
                                ?>
                                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <!-- Include Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>





    <script>
        $(document).ready(function() 
        {
            refreshSubjectTable();

    $('#pksubjectid').select2({
    placeholder: 'Select subject'
    });


            $('#image').change(function() 
            {
                if (this.files && this.files[0]) 
                {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#selected-img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });


            var i = 1;
            $('#add').click(function() 
            {
                i++;
                var newRow = '<tr id="row' + i + '">' +
                    '<td>' + i + '</td>' +
                    '<td><input type="text" name="city[]" placeholder="Enter your city" class="form-control name_list" /></td>' +
                    '<input type="hidden" name="studentcityid[]" value="">' + // New row, so set studentcityid to an empty value
                    '<td><input type="text" name="province[]" placeholder="Enter province" class="form-control name_list" /></td>' +
                    '<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td>' +
                    '</tr>';
                $('#dynamic_field tbody').append(newRow);
                updateSerialNumbers();
            });
            $(document).on('click', '.btn_remove', function() 
            {
                var button_id = $(this).attr("id");
                var cityId = $(this).data("city-id");

                if (cityId) {
                    $(this).closest("tr").remove();
                    updateSerialNumbers();
                } else {
                    $(this).closest("tr").remove();
                    updateSerialNumbers();
                }
            });


            function updateSerialNumbers() 
            {
                $('#dynamic_field tbody tr').each(function(sno) 
                {
                    $(this).find('td:first').text(sno++);
                });
            }

        });

        function insertsubject() 
        {

            var pksubjectid = $("#pksubjectid").val();
            var marks = $("#marks").val();
            var totalmarks = $("#totalmarks").val();
            if (pksubjectid !== '' && marks !== ''  && totalmarks !== '') 
            {
                var data = 
                {
                    pksubjectid: pksubjectid,
                    marks: marks,
                    totalmarks: totalmarks
                };
                $.ajax({
                    type: "POST",
                    url: "insertSubject.php",
                    data: data,
                    success: function(response) 
                    {

                        // alert(response);
                        $("#pksubjectid").val('');
                        $("#marks").val('');
                        $("#totalmarks").val('');
                        refreshSubjectTable();
                    },
                    error: function(error) {
                        alert('Error inserting data: ' + error);
                    }
                });
            } 
            else 
            {
                toastr.error("Please Fill Subject Name  Marks And Total Marks", "Error");
            }
        }


        function refreshSubjectTable() 
        {
            $.post("subjecttbl.php", function(data) 
            {
                $("#subjecttbl").html(data);
            });
        }
        //"Edit" button 
        function editSubject(studentsubjectid, pksubjectid, marks,totalmarks) 
        {
            $("#subjectId").val(studentsubjectid);
            $("#marks").val(marks);
            $("#totalmarks").val(totalmarks);
            $('#pksubjectid').val(pksubjectid).trigger("change");
            $("#addButton").text("Update").attr("onclick", "updateSubject()").removeClass("btn btn-primary").addClass("btn btn-warning");
        
        }

        //  "Update" button 
        function updateSubject() 
        {
            var studentsubjectid = $("#subjectId").val();
            var pksubjectid = $("#pksubjectid").val();
            var marks = $("#marks").val();
            var totalmarks = $("#totalmarks").val();
            // alert(subjectId);

            if (pksubjectid !== '' && marks !== '' && totalmarks !== '') 
            {
                var data = {
                    studentsubjectid: studentsubjectid,
                    pksubjectid: pksubjectid,
                    marks: marks,
                    totalmarks: totalmarks
                };

                $.ajax({
                    type: "POST",
                    url: "updateSubject.php",
                    data: data,
                    success: function(response) 
                    {
                        // alert("Subject updated.");
                        $("#subjectId").val('');
                        $("#subjectName").val('');
                        $("#marks").val('');
                        $("#totalmarks").val('');
                        $("#addButton").text("Add").attr("onclick", "insertsubject()").removeClass("btn btn-warning").addClass("btn btn-primary");
                        refreshSubjectTable();
                    },
                    error: function(error) 
                    {
                        alert('Error updating data: ' + error);
                    }
                });
            } 
            else 
            {
                toastr.error("Please Fill Subject Name And Marks", "Error");
                // alert('Please fill in both subjectName and marks.');
            }
        }
        //"Delete" button 
        function deleteSubject(subjectId) 
        {
            var confirmation = confirm("Are you sure you want to delete this subject?");

            if (confirmation) 
            {
                $.ajax({
                    type: "POST",
                    url: "deleteSubject.php",
                    data: 
                    {
                        subjectId: subjectId
                    },
                    success: function(response) 
                    {
                        // alert("Subject deleted.");
                        refreshSubjectTable();
                    },
                    error: function(error) 
                    {
                        alert('Error deleting data: ' + error);
                    }
                });
            }
        }

        // refresh the subject table
        function refreshSubjectTable() 
        {
            $.post("subjecttbl.php", function(data) 
            {
                $("#subjecttbl").html(data);
            });
        }
    </script>

    <script>
        $(function() {
            <?php
            if (isset($_SESSION['toastr'])) 
            {
                echo 'toastr.' . $_SESSION['toastr']['type'] . '("' . $_SESSION['toastr']['message'] . '", "' . $_SESSION['toastr']['title'] . '")';
                unset($_SESSION['toastr']);
            }
            ?>
        });
    </script>



</body>

</html>