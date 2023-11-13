<?php 
include "config/connection.php";
$countryId = $_GET['countryId'];

$sql = "SELECT id, pname FROM province WHERE countryid = '$countryId'";
$result = mysqli_query($connection, $sql);

$provinces = [];
while ($row = mysqli_fetch_array($result)) {
    $provinces[] = array(
        'id' => $row['id'],
        'pname' => $row['pname']
    );
}

echo json_encode($provinces);
?>
