<?php
@session_start();
include 'config/connection.php';
$sql = "";
if (isset($_SESSION['studentID']) && !empty($_SESSION['studentID'])) {
  $studentID = implode(',', $_SESSION['studentID']);
  $sql = "SELECT ss.`studentsubjectid`, ss.`fkstudentid`, ss.`fksubjectid`, ss.`marks`, ss.`totalmarks`, s.`subjectName`
  FROM `studentsubject` ss
  LEFT JOIN `subject` s ON ss.`fksubjectid` = s.`subjectid`
   WHERE fkstudentid IN ($studentID)";
  $result = mysqli_query($connection, $sql);
  while ($subject = mysqli_fetch_array($result)) {
    $pksubjectid = $subject['studentsubjectid'];
    $_SESSION['pksubjectid'][] = $pksubjectid;
  }
}

if (isset($_SESSION['pksubjectid'])) {

  $pksubjectids = implode(',', $_SESSION['pksubjectid']);
  $sql = "SELECT ss.`studentsubjectid`, ss.`fkstudentid`, ss.`fksubjectid`, ss.`marks`, ss.`totalmarks`, s.`subjectName`
  FROM `studentsubject` ss
  LEFT JOIN `subject` s ON ss.`fksubjectid` = s.`subjectid`
   WHERE studentsubjectid IN ($pksubjectids)";
}
if (!empty($sql)) {
  $result = mysqli_query($connection, $sql);
  if (!$result) {
    die('Error executing query: ' . mysqli_error($connection));
  }

  $i = 1;
  $sumObtainMarks = 0;
  $sumTotalMarks = 0;

  while ($subject = mysqli_fetch_array($result)) {
    $studentsubjectid = $subject['studentsubjectid'];
    $fksubjectid = $subject['fksubjectid'];
    $subjectName = $subject['subjectName'];
    $marks = $subject['marks'];
    $totalmarks = $subject['totalmarks'];
    if ($totalmarks > 0) {
      $percentage = ($marks / $totalmarks) * 100;
    } else {
      $percentage = 0;
    }

    $sumObtainMarks += $marks;
    $sumTotalMarks += $totalmarks;


 
?>
    <tr>
      <td><?php echo $i++; ?> <?php $studentsubjectid; ?></td>
      <td>
    <select class="form-control" onClick="highlightEdit(this);" onBlur="saveInlineEdit(this, 'fksubjectid', <?php echo $studentsubjectid; ?>)">
        <?php
        $select = mysqli_query($connection, 'SELECT * FROM subject');
        $subjects = mysqli_fetch_all($select, MYSQLI_ASSOC);

        foreach ($subjects as $subject) {
            $pksubjectid = $subject['subjectid'];
            $subjectName = $subject['subjectName'];

            echo '<option value="' . $pksubjectid . '">' . $subjectName . '</option>';
        }
        ?>
    </select>
</td>


      <td contenteditable="true" data-old_value="<?php echo $marks; ?>" onBlur="saveInlineEdit(this, 'marks', <?php echo $studentsubjectid; ?>)" onClick="highlightEdit(this);"><?php echo $marks; ?></td>
      <td contenteditable="true" data-old_value="<?php echo $totalmarks; ?>" onBlur="saveInlineEdit(this, 'totalmarks', <?php echo $studentsubjectid; ?>)" onClick="highlightEdit(this);"><?php echo $totalmarks; ?></td>
      <td><?php echo $percentage; ?></td>
      <td>
        <button type="button" class="btn btn-warning edit" onclick="editSubject(<?php echo $studentsubjectid; ?>, '<?php echo $pksubjectid; ?>', <?php echo $marks; ?>, <?php echo $totalmarks; ?>)">
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
  ?>
  <!-- Add a row for the sum -->
  <tr>
    <td colspan="2"><b>Total:</b></td>
    <td><b><?php echo $sumObtainMarks; ?></b></td>
    <td><b><?php echo $sumTotalMarks; ?></b></td>
    <td><b><?php echo ($sumObtainMarks / $sumTotalMarks) * 100; ?></b></td>
    <td></td>
    <td></td>
  </tr>
<?php
}
?>
<script type="text/javascript" src="script/inlinefunctions.js"></script>
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