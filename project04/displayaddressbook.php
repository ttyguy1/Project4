<?php
	// Insert the page header
	require_once('header.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.php');

	// Connect to the database 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 

	// Retrieve the user data from MySQL
	$query = "SELECT id, firstName, lastName, profilePicture FROM Contact WHERE firstName IS NOT NULL ORDER BY lastName ASC";
	$data = mysqli_query($dbc, $query);

	// Loop through the array of user data, formatting it as HTML
	echo '<h4>Address Book</h4>';
	echo '<table>';
	while ($row = mysqli_fetch_array($data)) {
		if (is_file(MM_UPLOADPATH . $row['profilePicture']) && filesize(MM_UPLOADPATH . $row['profilePicture']) > 0) {
			echo '<tr><td><img class="profilepic2" src="' . MM_UPLOADPATH . $row['profilePicture'] . '" alt="' . $row['firstName'] .
					" " . $row['lastName'] . '" /></td>';
			echo '<td class="nametd"><a class="viewContact" href="viewcontact.php?id=' . $row['id'] . '">' . $row['firstName'] . ' ' .
				$row['lastName'] . '</a></td></tr>';
		}
		else {
			echo '<tr><td><img class="profilepic2" src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['firstName'] . 
					" " . $row['lastName'] . '" /></td>';
			echo '<td class="nametd"><a class="viewContact" href="viewcontact.php?id=' . $row['id'] . '">' . $row['firstName'] . ' ' .
				$row['lastName'] . '</a></td></tr>';
		}
	}
	echo '</table>';

	mysqli_close($dbc);
?>

</body>
</html>
