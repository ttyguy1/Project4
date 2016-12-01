<?php
	require_once('authorize.php');
	// Insert the page header
	require_once('header.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.3.php');

	// Connect to the database 
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

	// Retrieve the score data from MySQL
	$query = "SELECT id, firstName, lastName, profilePicture FROM Contact WHERE firstName IS NOT NULL ORDER BY lastName ASC";
	$data = mysqli_query($dbc, $query);

	// Loop through the array of score data, formatting it as HTML 
	echo '<table>';
	
	while ($row = mysqli_fetch_array($data)) {
		
		if (is_file(MM_UPLOADPATH . $row['profilePicture']) && filesize(MM_UPLOADPATH . $row['profilePicture']) > 0) {
			echo '<tr><td><img class="profilepic2" src="' . MM_UPLOADPATH . $row['profilePicture'] . '" alt="' . $row['firstName'] .
					" " . $row['lastName'] . '" /></td>';
			echo '<td class="nametd">' . $row['firstName'] . ' ' . $row['lastName'] . '<a id="removecontactlink" href="removecontact.php?id=' . $row['id'] . '&amp;firstName=' . $row['firstName'] .
    							'&amp;lastName=' . $row['lastName'] . '">Remove</a>
				            <a href="#">Edit</a>
	    				</select></td></tr>';
		}
		
		else {
			echo '<tr><td><img class="profilepic2" src="' . MM_UPLOADPATH . 'nopic.jpg' . '" alt="' . $row['firstName'] . 
					" " . $row['lastName'] . '" /></td>';
			echo '<td class="nametd">' . $row['firstName'] . ' ' . $row['lastName'] . '
						<select> 
							<option selected disabled><a href="#">Actions</a></option>
	            			<option value="remove"><a href="#">Remove</a></option>
				            <option value="edit"><a href="#">Edit</a></option>
	    				</select></td></tr>';
		}
	}

	echo '</table>';

	mysqli_close($dbc);
?>

</body> 
</html>
