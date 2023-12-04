<?php
/*

Template Name: Sunny Spot Accommodation PHP Template
Description: Sunny Spot Accommodation live page to guests.
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

// Fetch data from the Cabin table and include the necessary joins
$sql = "SELECT Cabin.*, GROUP_CONCAT(Inclusions.incName) AS inclusionDetails
        FROM Cabin
        LEFT JOIN Junction ON Cabin.cabinID = Junction.cabinID
        LEFT JOIN Inclusions ON Junction.incID = Inclusions.incID
        GROUP BY Cabin.cabinID";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sunny Spot Accommodation</title>
    <link rel="icon" type="image/x-icon" href="https://sunnyspot.sirenwatcher.dev/wp-content/uploads/2023/11/favicon.ico">
    <link rel="stylesheet" type="text/css" href="https://sunnyspot.sirenwatcher.dev/wp-content/themes/hello-elementor/public.css">
    <script src="https://sunnyspot.sirenwatcher.dev/wp-content/themes/hello-elementor/cabins.js"></script>
</head>

<body>
    <header>
        <img src="https://sunnyspot.sirenwatcher.dev/wp-content/uploads/2023/11/sunny_spot_holidays_banner.webp">
    </header>

    <section>
        <?php
        // Loop through the results and generate HTML for each cabin
        while ($row = $result->fetch_assoc()) {
            echo "<article>";
            echo "<h2>" . $row['cabinType'] . "</h2>";
            echo "<img src='" . $row['photo'] . "' alt='" . $row['cabinType'] . "'>";
            echo "<p><span>Details: </span>" . $row['cabinDescription'] . "</p>";
            echo "<p><span>Price per night: </span>$" . $row['pricePerNight'] . "</p>";
            echo "<p><span>Price per week: </span>$" . $row['pricePerWeek'] . "</p>";
            echo "<p><span>Inclusions: </span>";
            if (!empty($row['inclusionDetails'])) {
                echo str_replace(',', ', ', $row['inclusionDetails']);
            } else {
                echo "None";
            }
            echo ".</p>";
            echo "</article>";
        }
        ?>
    </section>

    <footer>
    	<a href="https://sunnyspot.sirenwatcher.dev/sunny-spot-login/">Login</a> | <a href="https://sunnyspot.sirenwatcher.dev/sunny-spot-accommodation/">Accommodation</a>
    </footer>
    
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
