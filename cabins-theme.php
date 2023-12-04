<?php
/*
Template Name: Cabins PHP Template
Description: Custom functionality for cabins.
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

// If not logged in, redirect to the login page
/* (broken) if (!isset($_SESSION['userName'])) {
    error_log('Redirecting to login page because userName session is not set.');
    wp_redirect(home_url('/sunny-spot-login/'));
    exit;
}*/

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == "cabins_plugin_form") {
    // Validate and sanitize input data
    $actionType = sanitize_text_field($_POST['actionType']);
    $cabinType = sanitize_text_field($_POST['cabinType']);
    $cabinDescription = sanitize_textarea_field($_POST['description']);
    $pricePerNight = intval($_POST['pricePerNight']);
    $pricePerWeek = intval($_POST['pricePerWeek']);
    $inclusions = isset($_POST['inclusions']) ? $_POST['inclusions'] : array();
    
    // Validate and sanitize numeric input
    $pricePerNight = isset($_POST['pricePerNight']) ? intval($_POST['pricePerNight']) : 0;
    $pricePerWeek = isset($_POST['pricePerWeek']) ? intval($_POST['pricePerWeek']) : 0;
    
    // Check for non-negative values
    if ($pricePerNight < 0 || $pricePerWeek < 0) {
    echo "Price values cannot be negative.";
    // Handle the error or redirect as needed
    exit;
    }
    
    // Check if Price Per Week is more than 5 times Price Per Night
    if ($pricePerWeek > 5 * $pricePerNight) {
    echo "Price Per Week cannot be more than 5 times Price Per Night.";
    // Handle the error or redirect as needed
    exit;
    }
    
    $inclusions = isset($_POST['inclusions']) ? $_POST['inclusions'] : array();

    // Assuming you have a valid $conn connection object

    // Handle "Add New Cabin" option
    if ($actionType == "AddNewCabin") {
        // Retrieve other form data
        $newCabinType = sanitize_text_field($_POST["newCabinType"]);

        // Insert the new cabin into the database
        $sqlNewCabin = "INSERT INTO Cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek) VALUES (?, ?, ?, ?)";
        $stmtNewCabin = $conn->prepare($sqlNewCabin);

        if ($stmtNewCabin) {
            // Bind parameters to the statement
            $stmtNewCabin->bind_param("ssdd", $newCabinType, $cabinDescription, $pricePerNight, $pricePerWeek);
            // Execute the statement
            $stmtNewCabin->execute();

            // Check for success
            if ($stmtNewCabin->affected_rows > 0) {
                // New cabin record inserted successfully

                // Get the ID of the inserted record
                $newCabinID = $stmtNewCabin->insert_id;

                // Insert into Cabin_Inclusions table for the new cabin
                foreach ($inclusions as $incID) {
                    $sqlNewCabinInclusions = "INSERT INTO Cabin_Inclusions (cabinID, incID) VALUES (?, ?)";
                    $stmtNewCabinInclusions = $conn->prepare($sqlNewCabinInclusions);

                    if ($stmtNewCabinInclusions) {
                        $stmtNewCabinInclusions->bind_param("ii", $newCabinID, $incID);
                        $stmtNewCabinInclusions->execute();
                        $stmtNewCabinInclusions->close();
                    } else {
                        // Log or handle the error for inner statement preparation
                    }
                }

                // Redirect or display success message for the new cabin
            } else {
                // Error handling if the insert for the new cabin was not successful
                error_log('Failed to insert new cabin record: ' . $stmtNewCabin->error);
    		    echo "Failed to add a new cabin.";
            }

            // Close the statement for the new cabin
            $stmtNewCabin->close();
        } else {
            // Error handling if the statement preparation for the new cabin failed
            error_log('Failed to insert new cabin record: ' . $stmtNewCabin->error);
    		echo "Failed to add a new cabin.";
        }
    } else {
        // Existing cabin logic
        $sql = "INSERT INTO Cabin (cabinType, cabinDescription, pricePerNight, pricePerWeek) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Sunny Spot Dashboard</title>
    <link rel="icon" type="image/x-icon" href="<?php echo site_url('/wp-content/uploads/2023/11/favicon.ico'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/cabins.css">
    <script src="<?php echo get_template_directory_uri(); ?>/cabins.js"></script>
</head>

    <body>
        <header>
            <img src="<?php echo site_url('/wp-content/uploads/2023/11/sunny_spot_holidays_banner.webp'); ?>">
            <a href="<?php echo esc_url(home_url('/sunny-spot-login/')); ?>">Login</a> | <a href="<?php echo esc_url(home_url('/sunny-spot-accommodation/')); ?>">Accommodation</a> | <a href="<?php echo esc_url(home_url('/logout/')); ?>">Logout</a>
        </footer>
        </header>

        <div class="content">
            <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" enctype="multipart/form-data" id="cabins-form">
                <?php wp_nonce_field('cabins_plugin_form_nonce', 'cabins_plugin_form_nonce', true, true); ?>
                <input type="hidden" name="action" value="cabins_plugin_form">
                
                <label for="actionType">Select Action:</label>
                <select id="actionType" class="actionType" name="actionType">
                    <option value="UpdateCabin">Update cabin</option>
                    <option value="AddNewCabin">Add new cabin</option>
                    <option value="DeleteCabin">Delete cabin</option>
                </select><br>

                <div id="cabinTypeInput" style="display: none;">
                    <label for="newCabinType">Enter New Cabin Type:</label>
                    <input type="text" class="newCabinType" name="newCabinType"><br>
                </div>

                <label for="cabinType">Cabin Type:</label>
                <select id="cabinType" class="cabin" name="cabinType">
                    <option value="Standard cabin">Standard cabin – sleeps 4</option>
                    <option value="Standard open plan cabin">Standard open plan cabin – sleeps 4</option>
                    <option value="Deluxe cabin">Deluxe cabin – sleeps 4</option>
                    <option value="Villa">Villa – sleeps 4</option>
                    <option value="Spa villa">Spa villa – sleeps 4</option>
                    <option value="Slab powered site">Slab powered site</option>
                </select><br>
            
                <label for="description">Description:</label>
                <textarea id="description" class="description" name="description"></textarea><br>
            
                <label for="pricePerNight">Price Per Night:</label>
                <input id="pricePerNight" type="number" class="night" name="pricePerNight"><br>
            
                <label for="pricePerWeek">Price Per Week:</label>
                <input id="pricePerWeek" type="number" class="week" name="pricePerWeek"><br>
            
                <label for="inclusions">Cabin Inclusions:</label>
                <select id="inclusions" class="inclusions" name="inclusions[]" multiple size="14">
                    <option value="Air conditioner">Air conditioner</option>
                    <option value="Linen">Linen</option>
                    <option value="Veranda">Veranda</option>
                    <option value="Bunk bed">Bunk bed</option>
                    <option value="Ceiling fans">Ceiling fans</option>
                    <option value="Clock radio">Clock radio</option>
                    <option value="Dining facilities">Dining facilities</option>
                    <option value="Dishwasher">Dishwasher</option>
                    <option value="DVD Player">DVD Player</option>
                    <option value="Foxtel">Foxtel</option>
                    <option value="Fridge/Freezer">Fridge/Freezer</option>
                    <option value="Hair dryer">Hair dryer</option>
                    <option value="Ironing Facilities">Ironing Facilities</option>
                    <option value="Microwave">Microwave</option>
                </select><br>

                <label for="cabinImage">Cabin Image:</label>
                <div class="imagebutton">
                    <input type="file" id="cabinImage" class="image" name="cabinImage" accept=".jpg, .jpeg, .png, .tiff, .webp, .svg, .heif, .heic" style="display: none;">
                    <label for="cabinImage" id="uploadButton" class="imagebutton">Choose File</label>
                </div>
            
                <br><br>
                <input type="submit" class="submit" value="Submit">
            </form>

        <br>
        
    </div>
        
    </body>
    </html>

<?php
// Close the connection
$conn->close();
?>
