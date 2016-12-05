<?php
	// Insert the page header
	require_once('header.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.1.php');

	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if (isset($_POST['submit'])) {
		// Grab the profile data from the POST
		$first_name = mysqli_real_escape_string($dbc, trim($_POST['firstName']));
		$last_name = mysqli_real_escape_string($dbc, trim($_POST['lastName']));
		$birthday = mysqli_real_escape_string($dbc, trim($_POST['birthday']));
		$phone_number = mysqli_real_escape_string($dbc, trim($_POST['phoneNumber']));
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		$address = mysqli_real_escape_string($dbc, trim($_POST['address']));
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
		$state = mysqli_real_escape_string($dbc, trim($_POST['state']));
		$zip_code = mysqli_real_escape_string($dbc, trim($_POST['zipCode']));
		$profile_picture = mysqli_real_escape_string($dbc, trim($_FILES['profilePicture']['name']));
		$profile_picture_type = $_FILES['profilePicture']['type'];
		$profile_picture_size = $_FILES['profilePicture']['size']; 
		list($profile_picture_width, $profile_picture_height) = getimagesize($_FILES['profilePicture']['tmp_name']);
		$error = false;

		// Validate and move the uploaded picture file, if necessary
		if (!empty($profile_picture)) {
			if ((($profile_picture_type == 'image/gif') || ($profile_picture_type == 'image/jpeg') || ($profile_picture_type == 'image/pjpeg') ||
				($profile_picture_type == 'image/png')) && ($profile_picture_size > 0) && ($profile_picture_size <= MM_MAXFILESIZE) &&
				($profile_picture_width <= MM_MAXIMGWIDTH) && ($profile_picture_height <= MM_MAXIMGHEIGHT)) {
				if ($_FILES['file']['error'] == 0) {
					// Move the file to the target upload folder
					$target = MM_UPLOADPATH . basename($profile_picture);
					if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $target)) {
						
					} else {
						// The new picture file move failed, so delete the temporary file and set the error flag
						@unlink($_FILES['profilePicture']['tmp_name']);
						$error = true;
						echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
					}
				}
			} else {
				// The new picture file is not valid, so delete the temporary file and set the error flag
				@unlink($_FILES['profilePicture']['tmp_name']);
				$error = true;
				echo '<p class="error">Your picture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
					' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
			}
		}

		// Update the profile data in the database
		if (!$error) {
			if (!empty($first_name) && !empty($last_name) && !empty($birthday) && !empty($phone_number) && !empty($email) 
					&& !empty($address) && !empty($city) && !empty($state) && !empty($zip_code)) {
				// Only set the picture column if there is a new picture
				if (!empty($profile_picture)) {
					$query = "INSERT INTO Contact SET firstName = '$first_name', lastName = '$last_name'," .
						" birthday = '$birthday', phoneNumber = '$phone_number', email = '$email', address = '$address'," .
						" city = '$city', state = '$state', zipCode = '$zip_code', profilePicture = '$profile_picture'";
				}
				else {
					$query = "INSERT INTO Contact SET firstName = '$first_name', lastName = '$last_name'," .
						" birthday = '$birthday', phoneNumber = '$phone_number', email = '$email', address = '$address'," .
						" city = '$city', state = '$state', zipCode = '$zip_code'";
				}
				mysqli_query($dbc, $query);

				// Confirm success with the user
				echo '<p id="completed">Your contcat has been successfully added to the address book. <a id="addressbookbutton" href="displayaddressbook.php">Address Book</a></p>';

				mysqli_close($dbc);
				exit();
			}
			else {
				echo '<p class="error">You must enter all of the profile data (the picture is optional).</p>';
			}
		}
	} // End of check for form submission

	mysqli_close($dbc);
	
?>

	<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<label for="firstName">First name:</label>
		<input type="text" id="firstName" name="firstName"/><br />
		<label for="lastName">Last name:</label>
		<input type="text" id="lastName" name="lastName"/><br />
		<label for="birthday">Birthday:</label>
		<input type="text" id="birthday" name="birthday" placeholder="YYYY-MM-DD" /><br />
		<label for="phoneNumber">Phone:</label>
		<input type="text" id="phoneNumber" name="phoneNumber"/><br />
		<label for="email">Email:</label>
		<input type="text" id="email" name="email"/><br />
		<label for="address">Address:</label>
		<input type="text" id="address" name="address"/><br />
		<label for="city">City:</label>
		<input type="text" id="city" name="city"/><br />
		<label for="state">State:</label>
		<input type="text" id="state" name="state" /><br />
		<label for="zipCode">Zip Code:</label>
		<input type="text" id="zipCode" name="zipCode"/><br />
		<label for="profilePicture">Picture:</label>
		<input type="file" id="profilePicture" name="profilePicture" /><br/>
		<input type="submit" value="Save Profile" name="submit" />
	</form>

</body>
</html>