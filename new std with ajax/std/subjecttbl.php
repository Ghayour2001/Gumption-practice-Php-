<?php
@session_start();
include 'config/connection.php';

// Initialize the $sql variable
$sql = "";

if (isset($_SESSION['studentID']) && !empty($_SESSION['studentID'])) 
{
  $studentID = implode(',', $_SESSION['studentID']);
  $sql = "SELECT * FROM studentsubject WHERE fkstudentid IN ($studentID)";
  $result = mysqli_query($connection, $sql);
  while ($subject = mysqli_fetch_array($result)) {
    $pksubjectid = $subject['studentsubjectid'];
    $_SESSION['pksubjectid'][] = $pksubjectid;
  }
}

if (isset($_SESSION['pksubjectid'])) 
{
  $pksubjectids = implode(',', $_SESSION['pksubjectid']);
  $sql = "SELECT * FROM studentsubject WHERE studentsubjectid IN ($pksubjectids)";
}

if (!empty($sql)) { // Check if $sql is not empty before executing the query
  $result = mysqli_query($connection, $sql);
  if (!$result) {
    die('Error executing query: ' . mysqli_error($connection));
  }

  $i = 1;
  while ($subject = mysqli_fetch_array($result)) 
  {
    $studentsubjectid = $subject['studentsubjectid'];
    $subjectName = $subject['subjectName'];
    $marks = $subject['marks'];
?>
  <tr>
    <td><?php echo $i++; $studentsubjectid?></td>
    <td><?php echo $subjectName; ?></td>
    <td><?php echo $marks; ?></td>
    <td>
      <button type="button" class="btn btn-warning edit" onclick="editSubject(<?php echo $studentsubjectid; ?>, '<?php echo $subjectName; ?>', <?php echo $marks; ?>)">
        Edit
      </button>
    </td>
    <td>
      <button type="button" class="btn btn-danger delete" data-subjectid="<?php echo $studentsubjectid; ?>" onclick="deleteSubject(<?php echo $studentsubjectid; ?>)">
        Delete
      </button>
    </td>
  </tr>
<?php
  }
}
?>
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
