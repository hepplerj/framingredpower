<?php include("../../includes/header.php"); ?>

<?php $thisPage="Browse"; ?>

</head>

<body>

<div id="mainBody">
	
	<?php include("../../includes/navigation.php"); ?>
	
	<div id="content">
		<div class="bodyContent">

<center><h2><strong>Search the Newspapers</strong></h2></center>
<p style="font-size: 1.0em; text-indent: 1em; text-align: left;">The search engine allows you to search across the entire newspaper collection for specific keywords, thus providing you a way to parse documents that are most useful to you.</p>

<!-- BEGINNING OF GOOGLE SEARCH -->
<p><center>

	<form action="" id="cse-search-box">
	  <div>
		<input type="hidden" name="cx" value="002700178549093654085:1fzk5j5robi" />
		<input type="hidden" name="cof" value="FORID:9" />
		<input type="hidden" name="ie" value="UTF-8" />
		<input type="text" name="q" size="31" />
		<input type="submit" name="sa" value="Search" />
	  </div>
	</form>
	<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=en"></script>
<!-- END OF GOOGLE SEARCH -->

</center></p>

<!-- BEGINNING OF SEARCH DISPLAY -->

<div id="cse-search-results" style="margin-right:10px"></div>
<script type="text/javascript">
  var googleSearchIframeName = "cse-search-results";
  var googleSearchFormName = "cse-search-box";
  var googleSearchFrameWidth = 600;
  var googleSearchDomain = "www.google.com";
  var googleSearchPath = "/cse";
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>

</div>
</div>
<!--BEGINNING OF FOOTER-->
	<?php include("../../includes/footer.php"); ?>

</body>
</html>
