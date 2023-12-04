<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sunny Spot Access Log</title>
</head>
    
<body>
    
    <h1>Sunny Spot Access Log</h1>
    <br><br>
    
<?php
/*

Template Name: Sunny Spot Admin Log
Description: Sunny Spot Accommodation admin login records.
Version: 1.0
Author: Siren Watcher

*/

// Connect to database
$servername = "localhost";
$username = "sirenwat_sunny";
$password = "85((2NypdS";
$dbname = "sirenwat_sunny";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT logID, staffID, loginDateTime, logoutDateTime FROM Log";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Log ID</th><th>Staff ID</th><th>Login Date and Time</th><th>Logout Date and Time</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["logID"] . "</td><td>" . $row["staffID"] . "</td><td>" . $row["loginDateTime"] . "</td><td>" . $row["logoutDateTime"] . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}

$conn->close();
?>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        footer {
            text-align: center;
        }
    </style>

<footer>
    <br><br>
    <a href="<?php echo esc_url(home_url('/sunny-spot-login/')); ?>">Login</a> | <a href="<?php echo esc_url(home_url('/sunny-spot-accommodation/')); ?>">Accommodation</a> | <a href="<?php 		echo esc_url(home_url('/logout/')); ?>">Logout</a>
<footer>

</body>
</html>

<?php
// Close the connection
$conn->close();
?>
