<?php 
include "config/connection.php";
$provinceId = $_GET['provinceId'];

$sql = "SELECT * FROM city WHERE provinceid = '$provinceId'";
$result = mysqli_query($connection, $sql);

$cities = [];
while ($row = mysqli_fetch_array($result)) {
    $cities[] = array(
        'id' => $row['id'],
        'cname' => $row['cname']
    );
}

echo json_encode($cities);
?>
