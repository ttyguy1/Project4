<?php
	// Insert the page header
	require_once('header.php');

	require_once('appvars.php');
	require_once('connectvars.php');

	// Show the navigation menu
	require_once('navmenu.3.php');
    
    if (isset($_GET['id']) && isset($_GET['firstName']) && isset($_GET['lastName'])) {
        // Grab the score data from the GET
        $id = $_GET['id'];
        $firstName = $_GET['firstName'];
        $lastName = $_GET['lastName'];
    }
    else if (isset($_POST['id'])) {
        // Grab the score data from the POST\
        $id = $_POST['id'];
        $firstName = $_POST['firtName'];
        $lastName = $_POST['lastName'];
    }
    else {
        echo '<p class="error">Sorry, no contact was specified for removal.</p>';
    }
    
    if (isset($_POST['submit'])) {
        if ($_POST['confirm'] === 'Yes') {
            // Connect to the database
            $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME)
                    or die('Connection Error');
            // Delete the score data from the database
            $query = "DELETE FROM Contact WHERE id = $id LIMIT 1";
            
            mysqli_query($dbc, $query)
                    or die('Query Error2');
            mysqli_close($dbc);
    
            // Confirm success with the user
            echo '<p class="removecontact">The contact ' . $firstName . ' for ' . $lastName . ' was successfully removed. <a class="backtocontactbutton" href="admin.php">Back to admin page</a></p>';
        } else {
            echo '<p class="error removecontact">The contact was not removed. <a class="backtocontactbutton" href="admin.php">Back to admin page</a></p>';
        }
    } else if (isset($id) && isset($firstName) && isset($lastName)) {
        echo '<div class="removecontact"><p id="removetitle">Are you sure you want to delete the following contact?</p><br/>';
        echo '<p id="title"><strong>First name: </strong>' . $firstName . '<br /><strong>Last name: </strong>' . $lastName . '</p>';
        echo '<form id="back" method="post" action="removecontact.php">';
        echo '<input class="removeinput" type="radio" name="confirm" value="Yes" /> Yes <br/><br/>';
        echo '<input class="removeinput" type="radio" name="confirm" value="No" checked="checked" /> No <br/><br/><br/>';
        echo '<input type="submit" value="Submit" name="submit" />';
        echo '<input type="hidden" name="id" value="' . $id . '" />';
        echo '<input type="hidden" name="firstName" value="' . $firstName . '" />';
        echo '<input type="hidden" name="lastName" value="' . $lastName . '" />';
        echo '</form><div>';
    }

?>

</body> 
</html>
