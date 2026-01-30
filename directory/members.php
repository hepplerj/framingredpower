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
	<div class="bodyContent">

<h2>American Indian Movement <br/>Member Directory</h2>
   <p>Browse or search the database of American Indian Movement members to learn more about the position of members within AIM, tribal affiliations, date of birth, place of birth, and other descriptive attributes.</p>

<form method="GET" action="http://segonku.unl.edu/~jheppler/frp/directory/members.php"> 
</p>
<h3>Last Name: <input type="text" name="last_name" size=25 maxlength=25 />
</h3>
 
    <input type="submit" name="submit" value="Search" />
	<input type="hidden" name="searching" value="yes" />
  </form>
<p>


<?php 
	$host = 'localhost';
	$user = 'jheppler';
	$pw = 't4abke';
	$db = 'aim_members';


	mysql_connect($host, $user, $pw)
		or die(mysql_error);
		mysql_select_db($db);


$myresults = mysql_query("SELECT * FROM aimdirectory ORDER BY Last_Name");
	if (!$myresults) {
		echo 'Could not run query: ' . mysql_error();
		exit;
	}

	print "<table border=0 cellpadding=3 cellspacing=3 width=100%";
	print "<tr><th>Name</th><th>Tribal Affiliation</th><th>Indian Name</th></tr>";


	$odd = false;
//	while($row = mysql_fetch_row($myresults))
        while( $row = mysql_fetch_array( $myresults, MYSQL_ASSOC ) ) {
                $lname = $row['last_name'];
                $fname = $row['first_name'];
				$indian_name = $row['indian_name'];
                $tribe = $row['tribe'];
				$gender = $row['gender'];
				$position = $row['position'];
				$home = $row['home'];
				$birth = $row['birth'];
				$death = $row['death'];
				$citizenship = $row['citizenship'];
                $odd = !$odd;
		print( "<tr" . (($odd) ? ' class="odd"' : '') . ">" );
                print( "<td>" );
                print( "<A HREF=\"directory/members.search.php?last_name=" . $lname . "&first_name=" . $fname . "&submit=Search&searching=yes\"> $fname $lname </A>" );
                print( "</td>" );
                print( "<td>" );
                print( $tribe );
                print( "</td>" );
				print( "<td>" );
                print( $indian_name );
                print( "</td>" );
                print( "</tr>");
	}
	
	print("</table>");

mysql_close();

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
