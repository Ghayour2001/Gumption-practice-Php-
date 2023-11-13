<?php
session_start();
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
        body 
        {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container 
        {
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
                                <th>S.No</th>
                                <th>Student Name</th>
                                <th>Gender</th>
                                <th>Games</th>
                                <th>Subject Name</th>
                                <th>Subject Marks</th>
                                <th>City</th>
                                <th>Province</th>
                                <th>Image</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($connection, 'SELECT s.studentid, s.studentname, s.gender, 
                            (
                            SELECT GROUP_CONCAT(DISTINCT name)
                            FROM games
                            WHERE FIND_IN_SET(games.id, s.fkgameid)
                            ) AS game_name,
                            GROUP_CONCAT(DISTINCT sub.subjectName) AS subjectNames, 
                            GROUP_CONCAT(DISTINCT ss.marks) AS marks, 
                            GROUP_CONCAT(DISTINCT sc.city) AS cities, 
                            GROUP_CONCAT(DISTINCT sc.province) AS provinces, 
                            s.image
                            FROM student s 
                            LEFT JOIN studentsubject ss ON s.studentid = ss.fkstudentid 
                            LEFT JOIN studentcity sc ON s.studentid = sc.fkstudentid
                            LEFT JOIN subject sub ON ss.fksubjectid = sub.subjectid
                            GROUP BY s.studentid, s.studentname, s.gender, s.fkgameid, s.image;
                     ');
                            $i=1;
                            while ($row = mysqli_fetch_array($result)) 
                            {
                            ?>
                                <tr>
                                    <td><?php echo $i++?></td>
                                    <td><?php echo $row['studentname']; ?></td>
                                    <td><?php echo $row['gender']; ?></td>
                                    <td><?php echo $row['game_name']; ?></td>
                                    <td><?php echo $row['subjectNames']; ?></td>
                                    <td><?php echo $row['marks']; ?></td>
                                    <td><?php echo $row['cities']; ?></td>
                                    <td><?php echo $row['provinces']; ?></td>
                                    <td><img src="<?php echo $row['image']; ?>" alt="Student Image" width="100"></td>
                                    <td><a class="btn btn-info" href="create.php?id=<?php echo $row['studentid']; ?>"><i class="fas fa-edit"></i></a></td>

                                    <td>
                                        <a class="btn btn-danger delete-btn" href="delete.php?id=<?php echo $row['studentid']; ?>" ><i class="fas fa-trash-alt"></i></a>
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
            // hideLoader();
            $('#student-table').DataTable();  
        });
       
    $(function(){
        <?php
        if(isset($_SESSION['toastr']))
        {
            echo 'toastr.'.$_SESSION['toastr']['type'].'("'.$_SESSION['toastr']['message'].'", "'.$_SESSION['toastr']['title'].'")';
            unset($_SESSION['toastr']);
        }
        ?>          
    });
</script>   

</body>

</html>