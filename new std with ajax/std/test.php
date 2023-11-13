<?php
// Assuming you have a MySQL database set up
$host = 'your_database_host';
$username = 'your_database_username';
$password = 'your_database_password';
$database = 'your_database_name';

// Create a database connection
$mysqli = new mysqli($host, $username, $password, $database);

// Check for a successful connection
if ($mysqli->connect_error) {
    die('Connection Error: ' . $mysqli->connect_error);
}

// Get data from the AJAX request
$subjectName = $_POST['subjectName'];
$marks = $_POST['marks'];

// Insert data into the database
$sql = "INSERT INTO your_table_name (subjectName, marks) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('si', $subjectName, $marks);

if ($stmt->execute()) {
    echo 'Data inserted successfully';
} else {
    echo 'Error: ' . $stmt->error;
}

// Close the database connection
$stmt->close();
$mysqli->close();
?>
