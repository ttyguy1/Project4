<?php
	// Insert the page header
	require_once('header.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.3.php');

	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	if (isset($_POST['submit'])) {
		// Grab the profile data from the POST
		$firstName = mysqli_real_escape_string($dbc, trim($_POST['firstName']));
		$lastName = mysqli_real_escape_string($dbc, trim($_POST['lastName']));
		$birthday = mysqli_real_escape_string($dbc, trim($_POST['birthday']));
		$phoneNumber = mysqli_real_escape_string($dbc, trim($_POST['phoneNumber']));
		$email = mysqli_real_escape_string($dbc, trim($_POST['email']));
		$address= mysqli_real_escape_string($dbc, trim($_POST['address']));
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
		$state = mysqli_real_escape_string($dbc, trim($_POST['state']));
		$zipCode = mysqli_real_escape_string($dbc, trim($_POST['zipCode']));
		$profilePicture = mysqli_real_escape_string($dbc, trim($_POST['profilePicture']));
		$new_profilePicture = mysqli_real_escape_string($dbc, trim($_FILES['new_profilePicture']['name']));
		$new_profilePicture_type = $_FILES['new_profilePicture']['type'];
		$new_profilePicture_size = $_FILES['new_profilePicture']['size']; 
		list($new_profilePicture_width, $new_profilePicture_height) = getimagesize($_FILES['new_profilePicture']['tmp_name']);
		$error = false;

		// Validate and move the uploaded profilePicture file, if necessary
		if (!empty($new_profilePicture)) {
			if ((($new_profilePicture_type == 'image/gif') || ($new_profilePicture_type == 'image/jpeg') || ($new_profilePicture_type == 'image/pjpeg') ||
				($new_profilePicture_type == 'image/png')) && ($new_profilePicture_size > 0) && ($new_profilePicture_size <= MM_MAXFILESIZE) &&
				($new_profilePicture_width <= MM_MAXIMGWIDTH) && ($new_profilePicture_height <= MM_MAXIMGHEIGHT)) {
				if ($_FILES['file']['error'] == 0) {
					// Move the file to the target upload folder
					$target = MM_UPLOADPATH . basename($new_profilePicture);
					if (move_uploaded_file($_FILES['new_profilePicture']['tmp_name'], $target)) {
						// The new profilePicture file move was successful, now make sure any old profilePicture is deleted
						if (!empty($profilePicture) && ($profilePicture != $new_profilePicture)) {
							@unlink(MM_UPLOADPATH . $profilePicture);
						}
					}
					else {
						// The new profilePicture file move failed, so delete the temporary file and set the error flag
						@unlink($_FILES['new_profilePicture']['tmp_name']);
						$error = true;
						echo '<p class="error">Sorry, there was a problem uploading your picture.</p>';
					}
				}
			}
			else {
				// The new profilePicture file is not valid, so delete the temporary file and set the error flag
				@unlink($_FILES['new_profilePicture']['tmp_name']);
				$error = true;
				echo '<p class="error">Your profilePicture must be a GIF, JPEG, or PNG image file no greater than ' . (MM_MAXFILESIZE / 1024) .
					' KB and ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels in size.</p>';
			}
		}

		// Update the profile data in the database
		if (!$error) {
			if (!empty($firstName) && !empty($lastName)  && !empty($birthday) && !empty($phoneNumber) && !empty($email) 
					&& !empty($address)  && !empty($city) && !empty($state) && !empty($zipCode)) {
				// Only set the profilePicture column if there is a new profilePicture
				if (!empty($new_profilePicture)) {
					$query = "UPDATE Contact SET firstName = '$firstName', lastName = '$lastName', birthday = '$birthday', " .
						" phoneNumber = '$phoneNumber', email = '$email', address = '$address', city = '$city', state = '$state'," .
						" zipCode = '$zipCode', profilePicture = '$new_profilePicture' WHERE id = '" . $_GET['id'] . "'";
				}
				else {
					$query = "UPDATE Contact SET firstName = '$firstName', lastName = '$lastName', birthday = '$birthday', " .
						" phoneNumber = '$phoneNumber', email = '$email', address = '$address', city = '$city', state = '$state'," .
						" zipCode = '$zipCode' WHERE id = '" . $_GET['id'] . "'";
				}
				mysqli_query($dbc, $query);

				// Confirm success with the user
				echo '<p>Your contact has been successfully updated. Would you like to <a href="viewcontact.php?id=' . $row['id'] . '">view your contact</a>?</p>';

				mysqli_close($dbc);
				exit();
			}
			else {
				echo '<p class="error">You must enter all of the contact data (the picture is optional).</p>';
			}
		}
	} // End of check for form submission
	else {
		// Grab the profile data from the database
		$query = "SELECT firstName, lastName, birthday, phoneNumber, email, address, city, state, zipCode, profilePicture FROM Contact WHERE id = '" . $_GET['id'] . "'";
		$data = mysqli_query($dbc, $query);
		$row = mysqli_fetch_array($data);

		if ($row != NULL) {
			$firstName = $row['firstName'];
			$lastName = $row['lastName'];
			$birthday = $row['birthday'];
			$phoneNumber = $row['phoneNumber'];
			$email = $row['email'];
			$address = $row['address'];
			$city = $row['city'];
			$state = $row['state'];
			$zipCode = $row['zipCode'];
			$profilePicture = $row['profilePicture'];
		}
		else {
			echo '<p class="error">There was a problem accessing your profile.</p>';
		}
	}

	mysqli_close($dbc);
?>

	<form  id="editform" enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MM_MAXFILESIZE; ?>" />
			<label for="firstname">First name:</label>
			<input type="text" id="firstname" name="firstname" value="<?php if (!empty($firstName)) echo $firstName; ?>" /><br />
			<label for="lastname">Last name:</label>
			<input type="text" id="lastname" name="lastname" value="<?php if (!empty($lastName)) echo $lastName; ?>" /><br />
			<label for="birthday">Birthday:</label>
			<input type="text" id="birthday" name="birthday" value="<?php if (!empty($birthday)) echo $birthday; else echo 'YYYY-MM-DD'; ?>" /><br />
			<label for="phoneNumber">Phone number:</label>
			<input type="text" id="phoneNumber" name="phoneNumber" value="<?php if (!empty($phoneNumber)) echo $phoneNumber; ?>" /><br />
			<label for="email">Email:</label>
			<input type="text" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /><br />
			<label for="address">Address:</label>
			<input type="text" id="address" name="address" value="<?php if (!empty($address)) echo $address; ?>" /><br />
			<label for="city">City:</label>
			<input type="text" id="city" name="city" value="<?php if (!empty($city)) echo $city; ?>" /><br />
			<label for="state">State:</label>
			<input type="text" id="state" name="state" value="<?php if (!empty($state)) echo $state; ?>" /><br />
			<label for="zipCode">Zip code:</label>
			<input type="text" id="zipCode" name="zipCode" value="<?php if (!empty($zipCode)) echo $zipCode; ?>" /><br />
			<input type="hidden" name="profilePicture" value="<?php if (!empty($profilePicture)) echo $profilePicture; ?>" />
			<label for="new_profilePicture">Picture:</label>
			<input type="file" class="profilepic2" name="new_profilePicture" />
			<?php if (!empty($profilePicture)) {
				echo '<img class="profilepic2 editpic" src="' . MM_UPLOADPATH . $profilePicture . '" alt="Profile Picture" />';
			} ?>
		<input type="submit" value="Save Profile" name="submit" />
	</form>
</body>
</html>
