<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Framing Red Power</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<meta name="description" content="This site explores the history of the Trail of Broken Treaties, newspaper coverage, and the American Indian Movement's use of media in crafting their image." />
	
<meta name="keywords" content="American Indian Movement, AIM, history, newspaper, television, race, media, American Indian, Native American, politics, sixties, 1960, Richard Nixon, BIA, Bureau of Indian Affairs" />

	<link rel="stylesheet" href="http://segonku.unl.edu/~jheppler/frp/css/aimstyle.css">
	
	<base href="http://segonku.unl.edu/~jheppler/frp/" />
	
</head>

<body>

<div id="mainBody">

	
	<div id="banner"> 
		<span>Framing Red Power: Newspaper Coverage and the Trail of Broken Treaties</span> 
	</div>

<div id="menu">
		
    <ul id="main_menu"	>
      <li><a href="http://segonku.unl.edu/~jheppler/frp/" title="Return to the frontpage">HOME</a></li>
      <li><a href="http://segonku.unl.edu/~jheppler/frp/narrative/" title="Analysis of material">ANALYSIS</a></li>
      <li><a href="http://segonku.unl.edu/~jheppler/frp/archive/types.php" title="Digital archive of documents">DOCUMENTS</a></li>
      <li id="current"><a href="http://segonku.unl.edu/~jheppler/frp/sources/" title="Browse the project">BROWSE</a></li>
      <li><a href="http://framingredpower.wordpress.com/" title="Project blog">BLOG</a></li>
    </ul>

</div>

	<div id="content">
	<div class="body" id="noNav">

<?php 
    $host = 'localhost';
	$user = 'jheppler';
	$pw = 't4abke';
	$db = 'aim_members';

	$last_name = $_GET['last_name'];
	$first_name = $_GET['first_name'];

	$searching = $_GET['searching'];

// if ($searching == "yes")

//	{
//	echo "<h2>Results:</h2><p>";

//If they did not enter a search term we give them an error

//if ($last_name == "")

//	{
//	echo "<p>You forgot to enter a search term";
//	exit;
//	}

	mysql_connect($host, $user, $pw)
		or die(mysql_error);
		mysql_select_db($db);

// We preform a bit of filtering
	$last_name = strtoupper($last_name);
	$last_name = strip_tags($last_name);
	$last_name = trim ($last_name); 
	
        $first_name = strtoupper($first_name);
        $first_name = strip_tags($first_name);
        $first_name = trim( $first_name );
	
        $query = "SELECT * FROM aimdirectory WHERE last_name LIKE '%$last_name%' AND first_name LIKE '%$first_name%'";

//	$myresults = mysql_query("SELECT * FROM aimdirectory WHERE last_name LIKE '%$last_name%' AND first_name LIKE '%$first_name%'");
	$myresults = mysql_query($query);
	if (!$myresults) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}
	
	$numrows = mysql_num_rows($myresults);
	
    

	// Printing results in HTML
	
	$odd = false;
	// while($row = mysql_fetch_row($myresults))
		   while( $row = mysql_fetch_array( $myresults, MYSQL_ASSOC ) ) {
			$lname = $row['last_name'];
			$fname = $row['first_name'];
			$indian_name = $row['indian_name'];
            $tribe = $row['tribe'];
			$gender = $row['gender'];
			$position = $row['position'];
			$participation = $row['participation'];
			$home = $row['home'];
			$birth = $row['birth'];
			$death = $row['death'];
			$citizenship = $row['citizenship'];
			$odd = !$odd;
		
	print "<table border=0 cellpadding=3 cellspacing=3 width=100%";
	print "<tr><td><h1>$fname $lname (<i>$indian_name</i>)</h1></td></tr>";
	print "<tr><td><h2>Tribal Affiliation</h2></td></tr>";
	print "<tr><td><p>$tribe</p></td></tr>";
	print "<tr><td><h2>Position</h2></td></tr>";
	print "<tr><td><p>$position</p></td></tr>";
	print "<tr><td><h2>Participation</h2></td></tr>";
	print "<tr><td><p>$participation</p></td></tr>";
	print "<tr><td><h2>Home</h2></td></tr>";
	print "<tr><td><p>$home</p></td></tr>";
	print "<tr><td><h2>Birth</h2></td></tr>";
	print "<tr><td><p>$birth &#8212; $death</p></td></tr>";
	print "<tr><td><h2>Citizenship</h2></td></tr>";
	print "<tr><td><p>$citizenship</p></td></tr>";
	}
	
	print("</table>");
	



mysql_close();
//	}
?>

</div>
</div>

<div id="footcolor">
	<div id="footer">

		<ul>
			<li><a href="http://segonku.unl.edu/~jheppler/frp/secondary/" title="Relevant historiography">Historiography</a></li>
			<li><a href="http://segonku.unl.edu/~jheppler/frp/about/" title="About the project">About the Project</a></li>
			<li><a href="mailto:jason.heppler@huskers.unl.edu" title="Contact">Comments?</a></li>
		</ul>

 	<p class="copyStmt"><a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/us/80x15.png" /></a></p>

	</div>
	</div>
</div>
</div>

</body>
</html>
