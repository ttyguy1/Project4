<?php
	// Insert the page header
	require_once('viewcontactheader.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.php');

	// Connect to the database
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// Grab the profile data from the database
	if (!isset($_GET['id'])) {
		$query = "SELECT profilePicture, firstName, lastName, birthday, phoneNumber, email, address, city, state, zipCode FROM Contact WHERE id = '" . $_SESSION['id'] . "'";
	}
	else {
		$query = "SELECT profilePicture, firstName, lastName, birthday, phoneNumber, email, address, city, state, zipCode FROM Contact WHERE id ='" . $_GET['id'] . "'";
	}
	$data = mysqli_query($dbc, $query);

	if (mysqli_num_rows($data) == 1) {
		// The user row was found so display the user data
		$row = mysqli_fetch_array($data);
		echo '<table id="displayContactInfo" >';
		if (!empty($row['firstName'])) {
			echo '<tr><td class="label">First name:</td><td class="info">' . $row['firstName'] . '</td></tr>';
		}
		if (!empty($row['lastName'])) {
			echo '<tr><td class="label">Last name:</td><td class="info">' . $row['lastName'] . '</td></tr>';
		}
		if (!empty($row['birthday'])) {
			echo '<tr><td class="label">Birthday:</td><td class="info">' . $row['birthday'] . '</td></tr>';
		}
		if (!empty($row['phoneNumber'])) {
			echo '<tr><td class="label">Phone number:</td><td class="info">' . $row['phoneNumber'] . '</td></tr>';
		}
		if (!empty($row['email'])) {
    		echo '<tr><td class="label">Email:</td><td class="info">' . $row['email'] . '</td></tr>';
    	}
    	if (!empty($row['address'])) {
	      echo '<tr><td class="label">Address:</td><td class="info">' . $row['address'] . '</td></tr>';
	    }
		if (!empty($row['city']) || !empty($row['state']) || !empty($row['zipCode'])) {
			echo '<tr><td class="label">Location:</td><td class="info">' . $row['city'] . ', ' . $row['state'] . ' ' . $row['zipCode'] . '</td></tr>';
		}
		if (!empty($row['profilePicture'])) {
			echo '<tr><td class="label">Picture:</td><td><img class="profilepic2" src="' . MM_UPLOADPATH . $row['profilePicture'] .
				'" alt="Profile Picture" /></td></tr>';
		}
		echo '</table>';
	} // End of check for a single row of user results
	else {
		echo '<p class="error">There was a problem accessing your profile.</p>';
	}

	mysqli_close($dbc);
?>

</body>
</html>
