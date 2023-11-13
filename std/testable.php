<?php
session_start();
include 'config/connection.php';
if (isset($_REQUEST['id'])) {
    $studentID      =   $_REQUEST['id'];
    // echo $studentID ;

    $query = "SELECT
  s.studentid,
  s.studentname,
  s.gender,
  GROUP_CONCAT(DISTINCT ss.subjectName) AS subjectNames,
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
WHERE s.studentid = '1'
GROUP BY s.studentid, s.studentname, s.gender, s.image, s.fkgameid;
";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);
    print_r($row);
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
                                        while ($row1 = mysqli_fetch_array($result)) {
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
                                <input type="hidden" name="oldimage" class="form-control" value="">
                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <label class="col-sm-2 col-form-label">Preview</label>
                            <div class="col-sm-8">
                                <div class="image-div">
                                    <img src="images/avatar.png" alt="" id="selected-img">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="main2 col-md-12 mt-3 mb-5 border border-info">
                        <h3 class="text-success text-center ">Subject Chosen & Marks Obtained</h3>
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-2 col-form-label">Subject Name</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" name="subjectName[]" id="subjectName" placeholder="Subject name">
                            </div>
                            <label for="lastName" class="col-sm-2 col-form-label">Marks</label>
                            <div class="col-sm-4">
                                <input type="number" class="form-control" name="marks[]" id="marks" placeholder="Enter Marks">
                            </div>
                        </div>

                        <button type="button" id="addButton" class="btn btn-primary"> Add </button>

                        <table id="dataTable" class="table">
                            <thead>
                                <tr>
                                    <th>Subject Name</th>
                                    <th>Marks</th>
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
                                    <tr>
                                        <td>1</td>
                                        <td><input type="text" name="city[]" placeholder="Enter your city" class="form-control name_list" /></td>
                                        <td><input type="text" name="province[]" placeholder="Enter Province" class="form-control name_list" /></td>
                                        <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                                    </tr>
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
        // Initialize Select2
        $(document).ready(function() {
            $('#image').change(function() {
                if (this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#selected-img').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });



            $("#addButton").click(function() {
                var subjectName = $("#subjectName").val();
                var marks = $("#marks").val();
                if (subjectName && marks !== '') {
                    var newRow = $("<tr>");
                    var cols = "";
                    cols += '<td>' + subjectName + '<input type="hidden" name="subjectName[]" value="' + subjectName + '"></td>';
                    cols += '<td>' + marks + '<input type="hidden" name="marks[]" value="' + marks + '"></td>';
                    cols += '<td><button class="btn btn-warning edit">Edit</button></td>';
                    cols += '<td><button class="btn btn-danger delete">Delete</button></td>';
                    newRow.append(cols);
                    $("#subjecttbl").append(newRow);
                    $("#subjectName").val('');
                    $("#marks").val('');
                }
            });


            $("table").on("click", ".edit", function() {
                var currentRow = $(this).closest("tr");
                var subjectName = currentRow.find("td:eq(0)").text();
                var marks = currentRow.find("td:eq(1)").text();
                $("#subjectName").val(subjectName);
                $("#marks").val(marks);
                currentRow.remove();
            });

            $("table").on("click", ".delete", function() {
                $(this).closest("tr").remove();
            });

            var i = 1;
            $('#add').click(function() {
                i++;
                var newRow = '<tr id="row' + i + '">' +
                    '<td>' + i + '</td>' +
                    '<td><input type="text" name="city[]" placeholder="Enter your city" class="form-control name_list" /></td>' +
                    '<td><input type="text" name="province[]" placeholder="Enter province" class="form-control name_list" /></td>' +
                    '<td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td>' +
                    '</tr>';
                $('#dynamic_field tbody').append(newRow);
                updateSerialNumbers();
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id).remove();
                updateSerialNumbers();
            });

            function updateSerialNumbers() {
                $('#dynamic_field tbody tr').each(function(sno) {
                    $(this).find('td:first').text(sno++);
                });
            }

        });
    </script>


    <!-- 

<script>
function toastrMessege(type, message) 
{
  if (type === "success") {
    toastr.success(message);
  } else if (type === "error") {
    toastr.error(message);
  } else {
    console.error("Invalid toastr type: " + type);
  }
}
</script> -->

    <script>
        $(function() {
            <?php
            if (isset($_SESSION['toastr'])) {
                echo 'toastr.' . $_SESSION['toastr']['type'] . '("' . $_SESSION['toastr']['message'] . '", "' . $_SESSION['toastr']['title'] . '")';
                unset($_SESSION['toastr']);
            }
            ?>
        });
    </script>



</body>

</html>