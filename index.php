<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Cabin Information</title>
    <script src="cabins.js"></script>
    <link rel="stylesheet" type="text/css" href="cabins.css"> 
</head>
    
<body>
    <header>
        <img src="sunny_spot_holidays_banner.webp">
    </header>

<div class="content">
    <form method="post" action="cabins.php" enctype="multipart/form-data">
        
        <label for="cabinType">Cabin Type:</label><br>
        <select class="cabin" name="cabinType" required>
            <option value="Standard cabin">Standard cabin – sleeps 4</option>
            <option value="Standard open plan cabin">Standard open plan cabin – sleeps 4</option>
            <option value="Deluxe cabin">Deluxe cabin – sleeps 4</option>
            <option value="Villa">Villa – sleeps 4</option>
            <option value="Spa villa">Spa villa – sleeps 4</option>
            <option value="Slab powered site">Slab powered site</option>
        </select><br>
    
        <label for="description">Description:</label><br>
        <textarea class="description" name="description"></textarea><br>
    
        <label for="pricePerNight">Price Per Night:</label><br>
        <input type="number" class="night" name="pricePerNight"><br>
    
        <label for="pricePerWeek">Price Per Week:</label><br>
        <input type="number" class="week" name="pricePerWeek"><br>
    
        <label for="inclusions">Cabin Inclusions:</label><br>
        <select class="inclusions" name="inclusions[]" multiple size="14">
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

        <label for="cabinImage">Cabin Image:</label><br>
        <div class="imagebutton">
            <input type="file" class="image" id="fileInput" name="cabinImage" accept=".jpg, .jpeg, .png, .tiff, .webp, .svg, .heif, .heic" style="display: none;">
            <label for="fileInput" id="uploadButton" class="imagebutton">Choose File</label>
        </div>
    
        <br><br>
        <input type="submit" class="submit" value="Submit">
    </form>
</div>

</body>
</html>
