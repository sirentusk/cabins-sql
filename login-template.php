<?php
/*
Template Name: Custom Login Page
Description: Custom login page for managing cabins.
Version: 1.0
Author: Siren Watcher
*/

// Function to hash the password using SHA-256
function hashPassword($password) {
    return hash('sha256', $password);
}

// Function to get staffID from username
function get_staff_id_from_username($username) {
    global $conn; // Assuming $conn is your database connection

    $sql = "SELECT staffID FROM Admin WHERE userName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($staffID);
    $stmt->fetch();
    $stmt->close();

    return $staffID;
}

// Start a session if not already started
if (session_status() == PHP_SESSION_NONE) {
session_start();
}

// Establish the database connection
$servername = "localhost";
$username = "sirenwat_sunny";
$password = "85((2NypdS";
$dbname = "sirenwat_sunny";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // User input
    $input_username = isset($_POST['userName']) ? $_POST['userName'] : '';
    $input_password = isset($_POST['password']) ? $_POST['password'] : '';

    // Hash the entered password
    $input_password_hashed = hashPassword($input_password);

    // SQL query to retrieve hashed password from the Admin table
    $sql = "SELECT password FROM Admin WHERE userName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verify the password
    if ($hashed_password && hash_equals($hashed_password, $input_password_hashed)) {
        // Successful login
        error_log('Successful login');

        // Insert a new record into the "Log" table
        $staffID = get_staff_id_from_username($input_username); // You need to implement this function to get the staffID from the username
        $loginDateTime = current_time('mysql');

        $insert_log_sql = "INSERT INTO Log (staffID, loginDateTime) VALUES (?, ?)";
        $stmt = $conn->prepare($insert_log_sql);
        $stmt->bind_param("ss", $staffID, $loginDateTime);
        $stmt->execute();
        // Add error handling
        if ($stmt->error) {
        error_log('Insert log error: ' . $stmt->error);
        } else {
        error_log('Log entry inserted successfully.');
        }

        // Get the permalink for the dashboard page
        $dashboard_permalink = home_url('/sunny-spot-dashboard/');
        
        // Redirect to the dashboard page
        wp_redirect($dashboard_permalink);
        exit(); // Ensure that no other code is executed after the redirection
    } else {
        error_log('Incorrect username or password.');
        echo "Incorrect username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sunny Spot Login</title>
    <link rel="icon" type="image/x-icon" href="<?php echo site_url('/wp-content/uploads/2023/11/favicon.ico'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/cabins.css">
    <script src="<?php echo get_template_directory_uri(); ?>/cabins.js"></script>
</head>

<body>
    <header>
        <img src="<?php echo site_url('/wp-content/uploads/2023/11/sunny_spot_holidays_banner.webp'); ?>">
    </header>

    <div class="content">
        <form method="post" action="">
            <label for="userName">Username:</label>
            <input type="text" name="userName" id="userName" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <br><br>
            <input type="submit" value="Login">
        </form>

        <br>

        <footer>
            <a href="<?php echo esc_url(home_url('/sunny-spot-login/')); ?>">Login</a> | <a href="<?php echo esc_url(home_url('/sunny-spot-accommodation/')); ?>">Accommodation</a>
        </footer>
    </div>
    
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
